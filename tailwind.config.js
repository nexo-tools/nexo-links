import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Dark utilities follow the stamped [data-theme="dark"] (theme-init + toggle),
    // not prefers-color-scheme — so an explicit choice wins over the OS while the
    // OS preference is still honoured (theme-init stamps it before first paint).
    darkMode: ['selector', '[data-theme="dark"]'],

    theme: {
        extend: {
            // Instrument Sans first, the system stack behind it: the family face
            // is the one the ecosystem shares, and the fallback is what renders
            // while the woff2 is still in flight.
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },

            // Nexo brand + semantic colors → nexo-tokens.css variables. Additive:
            // the default palette (neutral/indigo/fuchsia/… used by the per-page
            // public-page theming) is left untouched on purpose. Chrome uses the
            // .nexo-* classes; content can use bg-surface / text-ink / bg-brand-600.
            colors: {
                brand: {
                    50: 'var(--nexo-violet-50)',
                    100: 'var(--nexo-violet-100)',
                    200: 'var(--nexo-violet-200)',
                    300: 'var(--nexo-violet-300)',
                    400: 'var(--nexo-violet-400)',
                    500: 'var(--nexo-violet-500)',
                    600: 'var(--nexo-violet-600)',
                    700: 'var(--nexo-violet-700)',
                    800: 'var(--nexo-violet-800)',
                    900: 'var(--nexo-violet-900)',
                    950: 'var(--nexo-violet-950)',
                },
                primary: {
                    DEFAULT: 'var(--nexo-primary)',
                    hover: 'var(--nexo-primary-hover)',
                    fg: 'var(--nexo-primary-fg)',
                    subtle: 'var(--nexo-primary-subtle)',
                    'subtle-fg': 'var(--nexo-primary-subtle-fg)',
                },
                ring: 'var(--nexo-ring)',
                link: {
                    DEFAULT: 'var(--nexo-link)',
                    hover: 'var(--nexo-link-hover)',
                },
                success: {
                    DEFAULT: 'var(--nexo-success)',
                    fg: 'var(--nexo-success-fg)',
                    subtle: 'var(--nexo-success-subtle)',
                    'subtle-fg': 'var(--nexo-success-subtle-fg)',
                },
                warning: {
                    DEFAULT: 'var(--nexo-warning)',
                    fg: 'var(--nexo-warning-fg)',
                    subtle: 'var(--nexo-warning-subtle)',
                    'subtle-fg': 'var(--nexo-warning-subtle-fg)',
                },
                danger: {
                    DEFAULT: 'var(--nexo-danger)',
                    fg: 'var(--nexo-danger-fg)',
                    subtle: 'var(--nexo-danger-subtle)',
                    'subtle-fg': 'var(--nexo-danger-subtle-fg)',
                },
                info: {
                    DEFAULT: 'var(--nexo-info)',
                    fg: 'var(--nexo-info-fg)',
                    subtle: 'var(--nexo-info-subtle)',
                    'subtle-fg': 'var(--nexo-info-subtle-fg)',
                },
                bg: {
                    DEFAULT: 'var(--nexo-bg)',
                    subtle: 'var(--nexo-bg-subtle)',
                },
                surface: {
                    DEFAULT: 'var(--nexo-surface)',
                    raised: 'var(--nexo-surface-raised)',
                    sunken: 'var(--nexo-surface-sunken)',
                },
                ink: 'var(--nexo-text)',
                muted: 'var(--nexo-text-muted)',
                subtle: 'var(--nexo-text-subtle)',
                line: {
                    DEFAULT: 'var(--nexo-border)',
                    strong: 'var(--nexo-border-strong)',
                },
                // Separate from `line`: a control's own outline has to clear
                // WCAG 1.4.11 (3:1), which the decorative border does not.
                control: 'var(--nexo-border-control)',
            },
        },
    },

    plugins: [forms],
};
