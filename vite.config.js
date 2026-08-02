import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // The family face (design.md, "Typography"), self-hosted: the woff2
            // files build into public/build/assets, so the page never asks a CDN
            // for them at runtime.
            fonts: [bunny('Instrument Sans', { weights: [400, 500, 600] })],
        }),
    ],
});
