{{-- Auth shell. Until 2026-08-02 this was still Breeze's: an oversized logo
     above a rounded-lg card, no header, no footer, and a legacy text switcher
     for language underneath — the only auth in the ecosystem where a person
     could not change theme at all, while this same tool served its 404s with
     full chrome.

     Now it is the family pattern: the shared header (wordmark, app-switcher,
     language, theme) and the shared footer around the canonical auth card. The
     card renders the page title as its <h1>, so every auth screen finally
     announces what it is — these views had no visible heading at all. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ ($title ?? null) ? $title.' · '.config('app.name') : config('app.name') }}</title>

        <!-- Scripts -->
        @include('partials.brand-head')

        @include('partials.beacon')

        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body class="flex min-h-screen flex-col bg-bg font-sans text-ink antialiased">
        <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-surface focus:px-4 focus:py-2 focus:text-link">
            {{ __('Skip to content') }}
        </a>

        <x-nexo-header brand="Nexo Links" mark="/ecosystem/nexolinks.svg" :home="url('/')" />

        <main id="contenido" class="flex flex-1 flex-col items-center justify-center px-4 py-10">
            <x-nexo-auth-card :title="$title">
                {{ $slot }}
            </x-nexo-auth-card>
        </main>

        <x-nexo-footer />
    </body>
</html>
