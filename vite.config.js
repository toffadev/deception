import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/client.css',
                'resources/css/admin.css',
                'resources/js/client.js',
                'resources/js/admin.js'
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        // Configuration Tailwind v4 avec le plugin Vite
        tailwindcss({
            // Spécifier les fichiers à scanner pour les classes
            content: [
                "./resources/**/*.blade.php",
                "./resources/**/*.js",
                "./resources/**/*.vue",
                "./resources/js/Client/**/*.vue",
                "./resources/js/Admin/**/*.vue",
            ]
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@client': resolve(__dirname, 'resources/js/Client'),
            '@admin': resolve(__dirname, 'resources/js/Admin'),
            // Aliases pour les composants Client
            '@/Components': resolve(__dirname, 'resources/js/Client/Components'),
            '@/Layouts': resolve(__dirname, 'resources/js/Client/Layouts'),
            '@/Pages': resolve(__dirname, 'resources/js/Client/Pages'),
            'Components': resolve(__dirname, 'resources/js/Client/Components'),
            'Layouts': resolve(__dirname, 'resources/js/Client/Layouts'),
            'Pages': resolve(__dirname, 'resources/js/Client/Pages'),
            // Aliases pour les composants Admin
            '@admin/Components': resolve(__dirname, 'resources/js/Admin/Components'),
            '@admin/Layouts': resolve(__dirname, 'resources/js/Admin/Layouts'),
            '@admin/Pages': resolve(__dirname, 'resources/js/Admin/Pages')
        },
    },
});