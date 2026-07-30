<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- The standard SEO block, not a hand-rolled head: /help is trilingual, so
         it needs the es/en/pt hreflang set the component emits. --}}
    <x-nexo-seo
        :title="__('nexo.help.title').' · '.config('app.name')"
        :description="__('What can I do in :app?', ['app' => config('app.name')])"
        :canonical="route('help')" />

    @include('partials.brand-head')

    @include('partials.beacon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-bg font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-2 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:shadow-lg">
        {{ __('Skip to content') }}
    </a>

    <x-nexo-header brand="{{ config('app.name') }}" mark="/ecosystem/nexolinks.svg" home="/">
        <x-slot:actions>
            @auth
                <a href="{{ route('dashboard') }}" class="nexo-btn nexo-btn--ghost">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('register') }}" class="nexo-btn nexo-btn--primary">{{ __('Create your page') }}</a>
            @endauth
        </x-slot:actions>
    </x-nexo-header>

    <main id="main" class="flex-1">
        <div class="nexo-help">
            <h1>{{ __('nexo.help.title') }}</h1>
            <p>{{ __('What can I do in :app?', ['app' => config('app.name')]) }}</p>

            @foreach ($faqs as $faq)
                <details class="nexo-help__item">
                    <summary>{{ $faq['q'] ?? '' }}</summary>
                    <div>{!! $faq['a'] ?? '' !!}</div>
                </details>
            @endforeach

            <div class="nexo-help__item nexo-help__contact">
                <div>
                    <strong>{{ __('nexo.help.contact_title') }}</strong>
                    <p>
                        <a class="nexo-btn nexo-btn--primary" href="{{ $contactUrl }}">
                            {{ __('nexo.help.contact_cta') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <x-nexo-footer />
</body>
</html>
