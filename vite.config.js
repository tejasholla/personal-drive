import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    base: process.env.VITE_ASSET_URL || process.env.ASSET_URL || './',
    server: false,
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react({
            strictMode: false
        }),
    ],
});
