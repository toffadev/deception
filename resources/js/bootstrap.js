import axios from 'axios';
import { Ziggy } from './ziggy';
window.axios = axios;
window.Ziggy = Ziggy;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configuration du CSRF token avec attente du DOM
function configureCsrfToken() {
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
        console.log('✅ Token CSRF configuré pour Axios:', csrfToken.content.substring(0, 10) + '...');
        return csrfToken.content;
    } else {
        console.error('❌ CSRF token not found in DOM');
        return null;
    }
}

// Fonction utilitaire pour récupérer un nouveau token CSRF
window.refreshCSRFToken = async function() {
    try {
        console.log('🔄 Récupération d\'un nouveau token CSRF...');
        
        // DIAGNOSTIC: Vérifier le token actuel avant refresh
        const oldToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('🔍 DIAGNOSTIC - Token actuel avant refresh:', oldToken?.substring(0, 10) + '...');
        
        // Utiliser une requête vers une route spécifique pour forcer un nouveau token
        const timestamp = Date.now();
        const response = await fetch(`/sanctum/csrf-cookie?t=${timestamp}`, {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            console.warn('⚠️ Route sanctum/csrf-cookie échouée, tentative alternative...');
            // Fallback : demander une nouvelle page
            const fallbackResponse = await fetch(`/?_token_refresh=${timestamp}`, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                    'Cache-Control': 'no-cache'
                },
                credentials: 'same-origin'
            });
            
            if (!fallbackResponse.ok) {
                throw new Error(`Impossible de récupérer nouveau token: ${fallbackResponse.status}`);
            }
            
            const html = await fallbackResponse.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            if (newToken && newToken !== oldToken) {
                // Mettre à jour le token
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (tokenMeta) {
                    tokenMeta.setAttribute('content', newToken);
                }
                
                if (window.axios) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
                }
                
                console.log('✅ Nouveau token CSRF (fallback):', newToken.substring(0, 10) + '...', '(remplace:', oldToken?.substring(0, 10) + '...)');
                console.log('🔍 DIAGNOSTIC - Tokens différents:', oldToken !== newToken ? '✅ OUI' : '❌ NON');
                return newToken;
            } else {
                throw new Error('Token CSRF fallback invalide ou identique');
            }
        } else {
            // Route sanctum réussie, demander maintenant une page pour obtenir le token
            const pageResponse = await fetch(`/?_after_csrf=${timestamp}`, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                credentials: 'same-origin'
            });
            
            const html = await pageResponse.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newToken = doc.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            if (newToken && newToken !== oldToken) {
                // Mettre à jour le token
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (tokenMeta) {
                    tokenMeta.setAttribute('content', newToken);
                }
                
                if (window.axios) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
                }
                
                console.log('✅ Nouveau token CSRF (sanctum):', newToken.substring(0, 10) + '...', '(remplace:', oldToken?.substring(0, 10) + '...)');
                console.log('🔍 DIAGNOSTIC - Tokens différents:', oldToken !== newToken ? '✅ OUI' : '❌ NON');
                return newToken;
            } else {
                console.warn('⚠️ Token récupéré identique à l\'ancien:', newToken?.substring(0, 10) + '...');
                return newToken || oldToken;
            }
        }
    } catch (error) {
        console.error('❌ Erreur lors de la récupération du token CSRF:', error);
        throw error;
    }
};

// Configurer immédiatement si possible, sinon attendre le DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', configureCsrfToken);
} else {
    configureCsrfToken();
}
