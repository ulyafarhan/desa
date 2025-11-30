import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'; // <--- WAJIB ADA

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    // --- BLOK INI HILANG DI FILE ANDA ---
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});