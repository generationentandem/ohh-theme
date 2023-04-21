import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                './src/assets/js/app.js',
                './src/assets/scss/app.scss',
                './src/assets/scss/editor.scss',
            ],
            publicDirectory: '/theme',
            buildDirectory: '/public',
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                src: './src/assets/images/**',
                dest: 'images/'
                },
                {
                    src: './src/assets/cool-timeline-pro/**',
                    dest: 'assets/'
                }
            ]
        })
    ],
});
