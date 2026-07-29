<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-nexo-seo :title="$content['title'].' · '.config('app.name')" :description="$content['intro']" />

    @include('partials.brand-head')

    @include('partials.beacon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-bg font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-2 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:shadow-lg">
        {{ __('Skip to content') }}
    </a>

    <x-nexo-header brand="Nexo Links" mark="/ecosystem/nexolinks.svg" :home="url('/')" />

    <main id="main" class="flex-1 px-4 py-10">
        <article class="mx-auto w-full max-w-2xl rounded-2xl bg-surface p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold">{{ $content['title'] }}</h1>
            <p class="mt-1 text-xs text-muted">{{ $updated }}</p>

            <p class="mt-4 text-sm leading-relaxed text-ink">{{ $content['intro'] }}</p>

            @foreach ($content['sections'] as $section)
                <section class="mt-6">
                    <h2 class="text-base font-semibold">{{ $section['h'] }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-muted">{{ $section['p'] }}</p>
                </section>
            @endforeach

            <p class="mt-8 border-t border-line pt-4 text-sm">
                <a href="{{ route('legal.privacy') }}" class="text-brand-700 underline dark:text-brand-400">{{ __('Privacy') }}</a>
                ·
                <a href="{{ route('legal.terms') }}" class="text-brand-700 underline dark:text-brand-400">{{ __('Terms') }}</a>
                ·
                <a href="{{ $contactUrl }}" class="text-brand-700 underline dark:text-brand-400">{{ __('nexo.help.contact_cta') }}</a>
            </p>
        </article>
    </main>

    <x-nexo-footer />
</body>
</html>
