import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-masterlists.js'],
            refresh: true,
        }),
    ],
     server: {
        host: '0.0.0.0',
        port: 5173,
        cors: true,
        origin: 'http://192.168.12.185:5173'
    }
});
