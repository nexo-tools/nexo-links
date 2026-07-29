{{-- Shared shell for every error page, so a 404 still feels like the product.
     `code`, `title` and `message` come from the per-code views in
     resources/views/errors/ — adding a status code is a one-liner there.

     The <head> is inlined rather than pulled from a partial because this repo
     has no shared partials/head; brand-head carries the theme-init + favicons.
     SEO is the standard component with noindex: an error page must never be
     indexed, and hreflang on a page that has no canonical content is noise. --}}
@props(['code', 'title', 'message'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-nexo-seo
        :title="$title.' · '.config('app.name')"
        :description="$message"
        :hreflang="false"
        :noindex="true" />

    @include('partials.brand-head')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-bg font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-2 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:shadow-lg">
        {{ __('Skip to content') }}
    </a>

    <x-nexo-header brand="Nexo Links" mark="/ecosystem/nexolinks.svg" :home="url('/')" />

    <main id="main" class="flex flex-1 flex-col items-center justify-center px-4 py-10 text-center">
        <p class="text-6xl font-bold tabular-nums text-brand-700 dark:text-brand-400">{{ $code }}</p>
        <h1 class="mt-4 text-2xl font-semibold">{{ $title }}</h1>
        <p class="mt-2 max-w-sm text-muted">{{ $message }}</p>

        <a href="{{ url('/') }}"
           class="mt-8 inline-flex items-center justify-center rounded-lg bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">
            {{ __('Back to home') }}
        </a>
    </main>

    <x-nexo-footer />
</body>
</html>
