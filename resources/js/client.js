import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route } from 'ziggy-js'; // ✅ Import Ziggy
import { Ziggy } from './ziggy';  // ✅ Import des routes générées (si nécessaire)
import { setupCSRFHandler } from './csrf-handler'; // ✅ Import du gestionnaire CSRF centralisé
import './csrf-diagnostic'; // 🔬 Diagnostic CSRF pour identifier les vrais problèmes

// DEBUG: Vérification de l'état au chargement
console.log('🔍 DIAGNOSTIC CSRF INERTIA CLIENT:', {
  'Token dans DOM': !!document.querySelector('meta[name="csrf-token"]'),
  'URL actuelle': window.location.href,
  'ReadyState': document.readyState
});

// Configuration du gestionnaire CSRF avec logs de debug pour le côté client
setupCSRFHandler(true);

// Suppression de l'erreur cosmétique 419 de la console
const originalConsoleError = console.error;
console.error = function(...args) {
    const message = args.join(' ');
    
    // Filtrer les erreurs 419 cosmétiques car elles sont gérées automatiquement
    if (message.includes('419') && message.includes('unknown status')) {
        // Log silencieux pour le debug si nécessaire
        console.log('🔇 Erreur 419 interceptée (récupération automatique en cours...)');
        return;
    }
    
    // Laisser passer toutes les autres erreurs
    originalConsoleError.apply(console, args);
};

createInertiaApp({
  resolve: (name) =>
    resolvePageComponent(
      `./Client/Pages/${name}.vue`,
      import.meta.glob('./Client/Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    // Configuration Inertia avec CSRF
    app.use(plugin);

    // ✅ Rendre `route` disponible dans tous les composants avec this.$route
    app.config.globalProperties.$route = (name, params, absolute, config = Ziggy) =>
      route(name, params, absolute, config);

    app.mount(el);
  },
  // Configuration globale pour TOUTES les requêtes Inertia
  progress: {
    color: '#4B5563',
  },
});
