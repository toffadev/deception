import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route } from 'ziggy-js';
import { Ziggy } from './ziggy';
import { setupCSRFHandler } from './csrf-handler'; // ✅ Import du gestionnaire CSRF centralisé

// Configuration du gestionnaire CSRF pour l'admin (sans logs de debug)
setupCSRFHandler(false);

createInertiaApp({
  resolve: (name) => resolvePageComponent(`./Admin/Pages/${name}.vue`, import.meta.glob('./Admin/Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    app.use(plugin);

    app.config.globalProperties.$route = (name, params, absolute, config = Ziggy) =>
      route(name, params, absolute, config);

    return app.mount(el);
  },
}); 