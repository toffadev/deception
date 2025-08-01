// 🔍 DIAGNOSTIC CSRF - Outil pour identifier le vrai problème

/**
 * Diagnostic complet du problème CSRF 419
 */
window.diagnoseCsrfProblem = async function() {
    console.log('🔬 DÉBUT DIAGNOSTIC CSRF 419');
    console.log('================================');
    
    // 1. Vérifier l'état actuel
    const currentToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('📊 Token actuel:', currentToken?.substring(0, 15) + '...');
    console.log('📊 Longueur token:', currentToken?.length);
    console.log('📊 URL actuelle:', window.location.href);
    console.log('📊 Session active:', !!document.cookie.match(/laravel_session|deception_session/));
    
    // 2. Test de récupération token frais
    console.log('\n🔄 TEST 1: Récupération token frais');
    try {
        const newToken = await window.refreshCSRFToken();
        console.log('✅ Nouveau token récupéré:', newToken?.substring(0, 15) + '...');
        console.log('📊 Tokens identiques?', currentToken === newToken ? '❌ OUI (problème!)' : '✅ NON (bon)');
    } catch (error) {
        console.error('❌ Échec récupération token:', error.message);
    }
    
    // 3. Test simple avec le token actuel
    console.log('\n🧪 TEST 2: Requête simple avec token actuel');
    try {
        const testResponse = await fetch('/', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ test: 'csrf' })
        });
        
        console.log('📊 Réponse status:', testResponse.status);
        if (testResponse.status === 419) {
            console.log('❌ Token actuel EXPIRÉ côté serveur');
        } else {
            console.log('✅ Token actuel VALIDE côté serveur');
        }
    } catch (error) {
        console.log('⚠️ Erreur test:', error.message);
    }
    
    // 4. Test avec token fraîchement récupéré
    console.log('\n🧪 TEST 3: Récupérer nouveau token et tester immédiatement');
    try {
        await window.refreshCSRFToken();
        const freshToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const testResponse2 = await fetch('/', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': freshToken,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ test: 'csrf_fresh' })
        });
        
        console.log('📊 Réponse status (token frais):', testResponse2.status);
        if (testResponse2.status === 419) {
            console.log('🚨 PROBLÈME CRITIQUE: Même les tokens frais sont rejetés!');
            console.log('🔍 Cause possible: Configuration Laravel, session corrompue, ou double envoi');
        } else {
            console.log('✅ Token frais FONCTIONNE - Le problème est le timing de refresh');
        }
    } catch (error) {
        console.log('⚠️ Erreur test token frais:', error.message);
    }
    
    // 5. Vérifier la configuration des cookies
    console.log('\n🍪 TEST 4: Analyse des cookies');
    const cookies = document.cookie.split(';').map(c => c.trim());
    const sessionCookie = cookies.find(c => c.includes('session'));
    const xsrfCookie = cookies.find(c => c.includes('XSRF'));
    
    console.log('📊 Cookie session:', sessionCookie ? '✅ Présent' : '❌ Absent');
    console.log('📊 Cookie XSRF:', xsrfCookie ? '✅ Présent' : '❌ Absent');
    console.log('📊 Tous cookies:', cookies.length, 'cookies trouvés');
    
    // 6. Test de double soumission
    console.log('\n⚡ TEST 5: Test double soumission (cause fréquente)');
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Lancer 2 requêtes en parallèle avec le même token
        const [resp1, resp2] = await Promise.all([
            fetch('/', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
                body: JSON.stringify({ test: 'double1' })
            }),
            fetch('/', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
                body: JSON.stringify({ test: 'double2' })
            })
        ]);
        
        console.log('📊 Requête 1 status:', resp1.status);
        console.log('📊 Requête 2 status:', resp2.status);
        
        if (resp1.status === 200 && resp2.status === 419) {
            console.log('🎯 PROBLÈME IDENTIFIÉ: Double soumission! Le token est à usage unique');
        }
    } catch (error) {
        console.log('⚠️ Erreur test double soumission:', error.message);
    }
    
    console.log('\n🔬 FIN DIAGNOSTIC CSRF 419');
    console.log('==============================');
    console.log('💡 Exécutez ce diagnostic quand l\'erreur 419 se produit pour identifier la cause exacte');
};

// Test spécifique pour identifier la cause exacte du problème
window.testTokenUsagePattern = async function() {
    console.log('🧪 TEST SPÉCIFIQUE - Pattern d\'utilisation token');
    console.log('==================================================');
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('📊 Token de test:', token?.substring(0, 15) + '...');
    
    // Test 1: Requête simple pour voir si le token fonctionne
    console.log('\n🔬 TEST 1: Requête POST simple');
    try {
        const response1 = await fetch('/auth/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email: 'test@test.com', password: 'test' })
        });
        
        console.log('📊 Réponse 1:', response1.status, response1.statusText);
        
        if (response1.status === 419) {
            console.log('❌ Token rejeté DÈS le premier usage');
            
            // Test immédiat avec nouveau token
            console.log('\n🔬 TEST 1B: Nouveau token immédiatement');
            await window.refreshCSRFToken();
            const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response1b = await fetch('/auth/login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': newToken,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ email: 'test@test.com', password: 'test' })
            });
            
            console.log('📊 Réponse 1B (token frais):', response1b.status, response1b.statusText);
            
            if (response1b.status === 419) {
                console.log('🚨 PROBLÈME GRAVE: Même les tokens fraîchement récupérés sont rejetés!');
                console.log('🔍 Causes possibles:');
                console.log('   - Session Laravel corrompue');
                console.log('   - Middleware CSRF mal configuré');
                console.log('   - Problème de domaine/cookies');
            } else {
                console.log('✅ Token frais fonctionne - Problème de timing détecté');
            }
        } else {
            console.log('✅ Token accepté');
            
            // Test 2: Réutiliser le même token pour voir s'il est à usage unique
            console.log('\n🔬 TEST 2: Réutilisation du même token');
            const response2 = await fetch('/auth/login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ email: 'test2@test.com', password: 'test2' })
            });
            
            console.log('📊 Réponse 2 (même token):', response2.status, response2.statusText);
            
            if (response2.status === 419) {
                console.log('🎯 PROBLÈME IDENTIFIÉ: Tokens CSRF à usage unique!');
                console.log('🔍 Laravel invalide le token après la première utilisation');
                console.log('💡 Solution: Récupérer nouveau token après chaque requête POST');
            }
        }
        
    } catch (error) {
        console.error('❌ Erreur test:', error);
    }
    
    // Test 3: Vérifier l'état de la session
    console.log('\n🔬 TEST 3: État de la session');
    try {
        const sessionResponse = await fetch('/', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        console.log('📊 Session response:', sessionResponse.status);
        console.log('📊 Session headers:', Object.fromEntries(sessionResponse.headers.entries()));
        
        if (sessionResponse.headers.get('set-cookie')) {
            console.log('📊 Nouveaux cookies définis:', sessionResponse.headers.get('set-cookie'));
        }
        
    } catch (error) {
        console.error('❌ Erreur test session:', error);
    }
    
    console.log('\n🔬 FIN TEST SPÉCIFIQUE');
    console.log('========================');
};

// Auto-diagnostic en cas d'erreur 419 détectée
let lastCsrfError = 0;
const originalFetch = window.fetch;
window.fetch = async (...args) => {
    try {
        const response = await originalFetch(...args);
        if (response.status === 419 && Date.now() - lastCsrfError > 5000) {
            lastCsrfError = Date.now();
            console.log('🚨 ERREUR 419 DÉTECTÉE - Lancement test spécifique...');
            setTimeout(() => window.testTokenUsagePattern(), 200);
        }
        return response;
    } catch (error) {
        return Promise.reject(error);
    }
};

console.log('🔬 Diagnostic CSRF chargé. Tapez window.diagnoseCsrfProblem() pour lancer le diagnostic complet.');