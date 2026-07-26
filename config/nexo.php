<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Footer Attribution
    |--------------------------------------------------------------------------
    |
    | Shown at the bottom of every public page. Each instance can brand it
    | via env, e.g. "powered by alvarocdev.com" pointing to your site.
    |
    */

    'attribution' => [
        'label' => env('NEXO_ATTRIBUTION_LABEL', 'made with Nexo Links'),
        'url' => env('NEXO_ATTRIBUTION_URL', 'https://alvarocdev.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Help Center Contact
    |--------------------------------------------------------------------------
    |
    | Where the /help "contact us" button points. A support URL wins; otherwise
    | a mailto: to the support email is used.
    |
    */

    'support_url' => env('NEXO_SUPPORT_URL'),
    'support_email' => env('NEXO_SUPPORT_EMAIL', 'hola@alvarocdev.com'),

    /*
    |--------------------------------------------------------------------------
    | Report Reasons
    |--------------------------------------------------------------------------
    |
    | Reasons a visitor can pick when reporting a page or a link. Values
    | are translation keys.
    |
    */

    'report_reasons' => [
        'broken' => 'Broken link',
        'malicious' => 'Malicious or scam',
        'abusive' => 'Abusive content',
        'spam' => 'Spam',
        'other' => 'Other',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Languages offered in the visible switcher. Keys are Laravel locales,
    | values are the native language names shown to users.
    |
    */

    'locales' => [
        'en' => 'English',
        'es' => 'Español',
        'pt' => 'Português',
    ],

    /*
    |--------------------------------------------------------------------------
    | Example Page
    |--------------------------------------------------------------------------
    |
    | Username linked from the landing page as the live example.
    |
    */

    'example_username' => env('NEXO_EXAMPLE_USERNAME', 'demo'),

    /*
    |--------------------------------------------------------------------------
    | Repository
    |--------------------------------------------------------------------------
    */

    'repository_url' => 'https://github.com/nexo-tools/nexo-links',

    /*
    |--------------------------------------------------------------------------
    | Cookieless Ecosystem Beacon (opt-in)
    |--------------------------------------------------------------------------
    |
    | Off by default so a standalone install phones nobody home. When enabled,
    | resources/js/nexo-beacon.js sendBeacon()s an anonymous pageview to the
    | Nexo Tools hub (see partials/beacon.blade.php — metas render only when on).
    |
    */

    'beacon' => [
        'enabled' => (bool) env('NEXO_BEACON_ENABLED', false),
        'endpoint' => (string) env('NEXO_BEACON_ENDPOINT', 'https://nexotools.alvarocdev.com/beacon'),
        'origin' => (string) env('NEXO_BEACON_ORIGIN', 'nexolinks'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Platforms
    |--------------------------------------------------------------------------
    |
    | Platforms available for the icons footer. "type" drives validation of
    | the stored value: handle, email or phone (E.164 with leading +).
    |
    */

    'social_platforms' => [
        'instagram' => ['label' => 'Instagram', 'url' => 'https://instagram.com/{value}', 'type' => 'handle'],
        'x' => ['label' => 'X', 'url' => 'https://x.com/{value}', 'type' => 'handle'],
        'tiktok' => ['label' => 'TikTok', 'url' => 'https://tiktok.com/@{value}', 'type' => 'handle'],
        'youtube' => ['label' => 'YouTube', 'url' => 'https://youtube.com/@{value}', 'type' => 'handle'],
        'github' => ['label' => 'GitHub', 'url' => 'https://github.com/{value}', 'type' => 'handle'],
        'linkedin' => ['label' => 'LinkedIn', 'url' => 'https://linkedin.com/in/{value}', 'type' => 'handle'],
        'twitch' => ['label' => 'Twitch', 'url' => 'https://twitch.tv/{value}', 'type' => 'handle'],
        'facebook' => ['label' => 'Facebook', 'url' => 'https://facebook.com/{value}', 'type' => 'handle'],
        'telegram' => ['label' => 'Telegram', 'url' => 'https://t.me/{value}', 'type' => 'handle'],
        'whatsapp' => ['label' => 'WhatsApp', 'url' => 'https://wa.me/{value}', 'type' => 'phone'],
        'email' => ['label' => 'Email', 'url' => 'mailto:{value}', 'type' => 'email'],
        'phone' => ['label' => 'Phone', 'url' => 'tel:{value}', 'type' => 'phone'],
        'website' => ['label' => 'Website', 'url' => '{value}', 'type' => 'url'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Phone Country Codes
    |--------------------------------------------------------------------------
    |
    | Codes offered in the phone/WhatsApp country selector. Users can still
    | type any full international number by picking their code here.
    |
    */

    'phone_prefixes' => [
        '+54' => '🇦🇷 +54',
        '+591' => '🇧🇴 +591',
        '+55' => '🇧🇷 +55',
        '+1' => '🇺🇸 +1',
        '+56' => '🇨🇱 +56',
        '+57' => '🇨🇴 +57',
        '+506' => '🇨🇷 +506',
        '+593' => '🇪🇨 +593',
        '+503' => '🇸🇻 +503',
        '+34' => '🇪🇸 +34',
        '+502' => '🇬🇹 +502',
        '+504' => '🇭🇳 +504',
        '+52' => '🇲🇽 +52',
        '+505' => '🇳🇮 +505',
        '+507' => '🇵🇦 +507',
        '+595' => '🇵🇾 +595',
        '+51' => '🇵🇪 +51',
        '+351' => '🇵🇹 +351',
        '+1809' => '🇩🇴 +1809',
        '+598' => '🇺🇾 +598',
        '+58' => '🇻🇪 +58',
        '+44' => '🇬🇧 +44',
        '+49' => '🇩🇪 +49',
        '+33' => '🇫🇷 +33',
        '+39' => '🇮🇹 +39',
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Presets
    |--------------------------------------------------------------------------
    |
    | Accent palettes a page owner can pick. "from"/"to" define the gradient
    | used for the avatar ring and highlighted links.
    |
    */

    'themes' => [
        'default' => ['label' => 'Nexo', 'from' => '#6366f1', 'to' => '#d946ef'],
        'ocean' => ['label' => 'Ocean', 'from' => '#0ea5e9', 'to' => '#6366f1'],
        'sunset' => ['label' => 'Sunset', 'from' => '#f97316', 'to' => '#db2777'],
        'forest' => ['label' => 'Forest', 'from' => '#10b981', 'to' => '#0d9488'],
        'mono' => ['label' => 'Mono', 'from' => '#171717', 'to' => '#525252'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reserved Usernames
    |--------------------------------------------------------------------------
    |
    | Usernames that can never be registered because they collide with app
    | routes or could be used to impersonate the site itself.
    |
    */

    'reserved_usernames' => [
        'about',
        'admin',
        'administrator',
        'analytics',
        'api',
        'app',
        'assets',
        'auth',
        'blog',
        'build',
        'confirm-password',
        'contact',
        'dashboard',
        'demo',
        'design',
        'docs',
        'email',
        'forgot-password',
        'help',
        'home',
        'l',
        'login',
        'logout',
        'mail',
        'nexo',
        'official',
        'password',
        'privacy',
        'profile',
        'register',
        'report',
        'reports',
        'reset-password',
        'root',
        'settings',
        'setup',
        'storage',
        'support',
        'system',
        'terms',
        'up',
        'verify',
        'verify-email',
        'www',
    ],

];
