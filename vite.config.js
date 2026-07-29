import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    optimizeDeps: {
        include: ['lodash-es'],
        exclude: ['amazon-ivs-player']
    },
    resolve: {
        alias: {
            'lodash': 'lodash-es',
            '@': '/resources/js',
        },
    },
    server: {
        host: true,
        port: 5173,
        strictPort: true,
        cors: true,
        origin: 'http://localhost:5173',
        hmr: {
            host: 'localhost',
            clientPort: 5173,
            protocol: 'ws',
        },
    },
    plugins: [
        tailwindcss(),
        laravel({
            input: 'resources/js/app.ts',
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
    ],
});
