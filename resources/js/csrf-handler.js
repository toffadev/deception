// Gestionnaire CSRF centralisé pour Inertia.js
import { router } from '@inertiajs/vue3';

// Map pour stocker les requêtes en cours de retry
const retryingRequests = new Map();

// Variables pour la gestion proactive du token
let tokenRefreshTimer = null;
let lastTokenRefresh = Date.now();
const TOKEN_REFRESH_INTERVAL = 15 * 60 * 1000; // 15 minutes (plus raisonnable)
const TOKEN_WARNING_INTERVAL = 10 * 60 * 1000; // 10 minutes (refresh préventif)

/**
 * Détermine si le token doit être rafraîchi
 */
function shouldRefreshToken() {
    const tokenAge = Date.now() - lastTokenRefresh;
    const shouldRefresh = tokenAge > TOKEN_WARNING_INTERVAL; // Refresh après 10 min
    
    return shouldRefresh;
}

/**
 * Configure la gestion automatique du CSRF pour Inertia.js
 * @param {boolean} enableDebugLogs - Activer les logs de debug
 */
export function setupCSRFHandler(enableDebugLogs = false) {
    const log = enableDebugLogs ? console.log : () => {};
    const logError = console.error;
    const logWarn = console.warn;

    // Refresh immédiat au chargement pour s'assurer d'avoir un token frais
    ensureFreshTokenOnLoad(enableDebugLogs);

    // Démarrer le refresh automatique proactif
    startProactiveTokenRefresh(enableDebugLogs);

    // Intercepteur global pour toutes les requêtes HTTP (Axios + Fetch)
    setupGlobalCSRFInterceptor(enableDebugLogs);

    // Injection automatique du token CSRF dans toutes les requêtes Inertia avec vérification proactive
    router.on('before', async (event) => {
        // Vérifier si le token doit être rafraîchi avant la requête
        await ensureFreshToken(enableDebugLogs);
        
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (enableDebugLogs) {
            log('🔄 REQUÊTE INERTIA:', {
                method: event.detail.visit.method,
                url: event.detail.visit.url,
                hasToken: !!token,
                tokenPreview: token ? token.substring(0, 10) + '...' : 'ABSENT',
                headers: event.detail.visit.headers,
                tokenAge: Math.round((Date.now() - lastTokenRefresh) / 1000 / 60) + ' min'
            });
        }
        
        if (token) {
            // Injecter le token CSRF dans TOUTES les requêtes Inertia
            event.detail.visit.headers = event.detail.visit.headers || {};
            event.detail.visit.headers['X-CSRF-TOKEN'] = token;
            event.detail.visit.headers['X-Requested-With'] = 'XMLHttpRequest';
            
            log('✅ Token CSRF ajouté');
        } else {
            logError('❌ TOKEN CSRF MANQUANT!');
        }
    });

    // Gestion intelligente des erreurs 419 avec retry automatique
    router.on('error', async (event) => {
        const response = event.detail.response;
        const visit = event.detail.visit;
        
        if (enableDebugLogs) {
            logError('💥 ERREUR INERTIA:', {
                status: response?.status,
                statusText: response?.statusText,
                url: visit?.url,
                method: visit?.method,
                sentHeaders: visit?.headers
            });
        }
        
        if (response?.status === 419) {
            await handleCSRFError(visit, enableDebugLogs);
        }
    });

    // Gestionnaire alternatif pour les cas où l'événement 'error' ne se déclenche pas
    router.on('exception', async (event) => {
        if (enableDebugLogs) {
            logError('⚠️ EXCEPTION INERTIA:', event.detail);
        }
        
        if (event.detail?.response?.status === 419) {
            await handleCSRFError(event.detail.visit, enableDebugLogs);
        }
    });

    // Fonction helper pour gérer les erreurs CSRF
    async function handleCSRFError(visit, enableDebugLogs) {
        const logWarn = console.warn;
        const logError = console.error;
        const log = enableDebugLogs ? console.log : () => {};
        
        logWarn('🚨 ERREUR 419 - Token CSRF expiré!');
        
        // Créer une clé unique pour cette requête
        const requestKey = `${visit.method}-${visit.url}-${JSON.stringify(visit.data || {})}`;
        
        // Éviter les retry en boucle infinie
        if (retryingRequests.has(requestKey)) {
            logError('❌ Tentative de retry déjà en cours pour cette requête, abandon...');
            retryingRequests.delete(requestKey);
            alert('Session expirée. La page va être rechargée pour obtenir un nouveau token CSRF.');
            window.location.reload();
            return;
        }
        
        try {
            // Marquer cette requête comme en cours de retry
            retryingRequests.set(requestKey, true);
            
            // Récupérer un nouveau token CSRF
            const newToken = await window.refreshCSRFToken();
            
            if (newToken) {
                logWarn('🔄 Relance automatique de la requête avec le nouveau token...');
                
                // Reconstruire la visite avec le nouveau token
                const newVisitOptions = {
                    method: visit.method || 'get',
                    data: visit.data || {},
                    headers: {
                        ...visit.headers,
                        'X-CSRF-TOKEN': newToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    preserveScroll: visit.preserveScroll,
                    preserveState: visit.preserveState,
                    replace: visit.replace,
                    only: visit.only,
                    except: visit.except,
                    onSuccess: visit.onSuccess,
                    onError: visit.onError,
                    onFinish: visit.onFinish
                };
                
                // Nettoyer la map et relancer la requête
                retryingRequests.delete(requestKey);
                
                // Utiliser router.visit pour relancer la requête
                router.visit(visit.url, newVisitOptions);
                
                log('✅ Requête relancée avec succès');
            } else {
                throw new Error('Impossible de récupérer un nouveau token CSRF');
            }
        } catch (error) {
            logError('❌ Erreur lors de la récupération du token CSRF:', error);
            retryingRequests.delete(requestKey);
            alert('Session expirée. La page va être rechargée pour obtenir un nouveau token CSRF.');
            window.location.reload();
        }
    }

    // Log des succès pour le debug
    if (enableDebugLogs) {
        router.on('success', (event) => {
            log('✅ Succès:', event.detail.visit?.method, event.detail.visit?.url);
        });
    }

    log('🛡️ Gestionnaire CSRF Inertia.js configuré');
}

/**
 * Intercepteur global pour toutes les requêtes HTTP (backup au cas où Inertia échoue)
 */
function setupGlobalCSRFInterceptor(enableDebugLogs = false) {
    const log = enableDebugLogs ? console.log : () => {};
    
    // Intercepteur Axios SIMPLIFIÉ avec diagnostic détaillé
    if (window.axios) {
        // Intercepteur de requête - injection simple avec logs diagnostics
        window.axios.interceptors.request.use(
            config => {
                const currentToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const tokenAge = Math.round((Date.now() - lastTokenRefresh) / 1000 / 60);
                
                if (['post', 'put', 'patch', 'delete'].includes(config.method?.toLowerCase())) {
                    log('🔍 AXIOS REQUEST DIAGNOSTIC:', {
                        method: config.method?.toUpperCase(),
                        url: config.url,
                        tokenPresent: !!currentToken,
                        tokenPreview: currentToken?.substring(0, 12) + '...',
                        tokenLength: currentToken?.length,
                        tokenAge: tokenAge + ' min',
                        timestamp: new Date().toISOString()
                    });
                    
                    if (currentToken) {
                        config.headers['X-CSRF-TOKEN'] = currentToken;
                        log('✅ AXIOS - Token injecté:', currentToken.substring(0, 12) + '...');
                    } else {
                        console.error('❌ AXIOS - AUCUN TOKEN DISPONIBLE!');
                    }
                }
                
                return config;
            },
            error => Promise.reject(error)
        );

        // Intercepteur de réponse - récupération 419 SILENCIEUSE
        window.axios.interceptors.response.use(
            response => response,
            async error => {
                if (error.response?.status === 419) {
                    const sentToken = error.config?.headers?.['X-CSRF-TOKEN'];
                    const currentToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    // Log diagnostic seulement si debug activé
                    if (enableDebugLogs) {
                        console.warn('🔄 CSRF 419 - Récupération automatique en cours...', {
                            url: error.config?.url,
                            method: error.config?.method?.toUpperCase(),
                            tokenSent: sentToken?.substring(0, 12) + '...',
                            tokenCurrent: currentToken?.substring(0, 12) + '...',
                            tokensIdentical: sentToken === currentToken,
                            tokenAge: Math.round((Date.now() - lastTokenRefresh) / 1000 / 60) + ' min'
                        });
                    }
                    
                    if (!error.config._retryAttempted) {
                        try {
                            const newToken = await window.refreshCSRFToken();
                            lastTokenRefresh = Date.now();
                            
                            if (newToken && error.config) {
                                error.config._retryAttempted = true;
                                error.config.headers['X-CSRF-TOKEN'] = newToken;
                                
                                log('✅ CSRF récupéré silencieusement, relance requête');
                                
                                // Relancer la requête SANS propager l'erreur originale
                                return window.axios.request(error.config);
                            }
                        } catch (retryError) {
                            console.error('❌ AXIOS - Échec définitif de récupération CSRF:', retryError);
                        }
                    }
                }
                
                return Promise.reject(error);
            }
        );
        
        log('🔧 Intercepteurs Axios configurés avec diagnostic détaillé');
    }
    
    // Intercepteur Fetch natif
    const originalFetch = window.fetch;
    window.fetch = async (input, init = {}) => {
        try {
            const response = await originalFetch(input, init);
            
            if (response.status === 419 && !response.url.includes('refreshCSRFToken')) {
                console.warn('🚨 FETCH - Erreur 419 détectée, tentative de récupération...');
                
                try {
                    const newToken = await window.refreshCSRFToken();
                    
                    if (newToken) {
                        // Mettre à jour les headers et relancer
                        const newInit = { ...init };
                        newInit.headers = { 
                            ...newInit.headers, 
                            'X-CSRF-TOKEN': newToken 
                        };
                        
                        console.log('🔄 FETCH - Relance de la requête avec nouveau token');
                        return originalFetch(input, newInit);
                    }
                } catch (retryError) {
                    console.error('❌ FETCH - Échec de récupération:', retryError);
                }
            }
            
            return response;
        } catch (error) {
            return Promise.reject(error);
        }
    };
    
    log('🔧 Intercepteur Fetch CSRF configuré');
}

/**
 * Démarre le système de refresh automatique proactif du token CSRF
 */
function startProactiveTokenRefresh(enableDebugLogs = false) {
    const log = enableDebugLogs ? console.log : () => {};
    
    // Nettoyer tout timer existant
    if (tokenRefreshTimer) {
        clearInterval(tokenRefreshTimer);
    }
    
    // Configurer le refresh automatique toutes les 15 minutes
    tokenRefreshTimer = setInterval(async () => {
        try {
            log('⏰ Refresh automatique du token CSRF (15 min)...');
            await window.refreshCSRFToken();
            lastTokenRefresh = Date.now();
            log('✅ Token CSRF automatiquement rafraîchi');
        } catch (error) {
            console.error('❌ Échec du refresh automatique:', error);
        }
    }, TOKEN_REFRESH_INTERVAL);
    
    // Refresh sur les événements de focus/visibilité pour gérer les onglets inactifs
    document.addEventListener('visibilitychange', async () => {
        if (!document.hidden && shouldRefreshToken()) {
            try {
                log('👁️ Page redevenue visible, vérification du token...');
                await window.refreshCSRFToken();
                lastTokenRefresh = Date.now();
                log('✅ Token rafraîchi après retour de focus');
            } catch (error) {
                console.error('❌ Échec du refresh au focus:', error);
            }
        }
    });
    
    window.addEventListener('focus', async () => {
        if (shouldRefreshToken()) {
            try {
                log('🔄 Fenêtre focusée, vérification du token...');
                await window.refreshCSRFToken();
                lastTokenRefresh = Date.now();
                log('✅ Token rafraîchi au focus');
            } catch (error) {
                console.error('❌ Échec du refresh au focus:', error);
            }
        }
    });
    
    log('🕒 Système de refresh configuré (auto: 15 min, préventif: 10 min) + diagnostic détaillé');
}

/**
 * S'assure que le token est frais avant une requête critique
 */
async function ensureFreshToken(enableDebugLogs = false) {
    const log = enableDebugLogs ? console.log : () => {};
    
    if (shouldRefreshToken()) {
        try {
            log('🔄 Token potentiellement expiré, refresh préventif...');
            await window.refreshCSRFToken();
            lastTokenRefresh = Date.now();
            log('✅ Token préventivement rafraîchi');
        } catch (error) {
            console.error('❌ Échec du refresh préventif:', error);
        }
    }
}

/**
 * S'assure d'avoir un token frais au chargement de la page
 */
async function ensureFreshTokenOnLoad(enableDebugLogs = false) {
    const log = enableDebugLogs ? console.log : () => {};
    
    try {
        log('🚀 Vérification du token CSRF au chargement...');
        await window.refreshCSRFToken();
        lastTokenRefresh = Date.now();
        log('✅ Token CSRF vérifié/rafraîchi au chargement');
    } catch (error) {
        console.warn('⚠️ Impossible de rafraîchir le token au chargement:', error);
    }
}