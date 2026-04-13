import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            // تجاهل المجلدات التي لا تحتاج لمراقبة تغييرات حية
            ignored: ['**/vendor/**', '**/node_modules/**', '**/storage/**'],
            // تفعيل الاستطلاع لتجنب استهلاك الـ inotify watchers
            usePolling: true,
            interval: 100,
        },
    },
});
