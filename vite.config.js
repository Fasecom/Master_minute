import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/base.css',
                'resources/css/nav.css',
                'resources/js/app.js',
                'resources/js/masters-search.js',
                'resources/js/phone-formatter.js',
            ],
            refresh: true,
        }),
    ],
});
