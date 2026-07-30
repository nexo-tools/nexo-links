<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ ($title ?? null) ? $title.' · '.config('app.name') : config('app.name') }}</title>

        @include('partials.brand-head')

        @include('partials.beacon')

        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body class="flex min-h-screen flex-col bg-bg font-sans text-ink antialiased">
        <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-2 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:shadow-lg">
            {{ __('Skip to content') }}
        </a>

        {{-- Owner chrome: the standard Nexo header (wordmark + primary nav +
             app-switcher + locale + theme toggle + account menu). The violet brand
             lives here; the public link-in-bio pages keep their own per-page theme. --}}
        <x-nexo-header brand="{{ config('app.name') }}" mark="/ecosystem/nexolinks.svg" home="{{ route('dashboard') }}">
            <x-slot:nav>
                @foreach ([['dashboard', __('Links')], ['analytics', __('Analytics')], ['design.edit', __('Design')]] as [$route, $label])
                    <a href="{{ route($route) }}"
                       @if (request()->routeIs($route)) aria-current="page" @endif
                       @class([
                           'inline-flex items-center rounded-md px-3 py-2 text-sm font-medium',
                           'text-primary' => request()->routeIs($route),
                           'text-muted hover:text-ink' => ! request()->routeIs($route),
                       ])>{{ $label }}</a>
                @endforeach
            </x-slot:nav>

            <x-slot:actions>
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-surface-raised">
                    <x-slot name="trigger">
                        <button type="button" class="nexo-btn nexo-btn--ghost">
                            <span class="max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('help')">{{ __('nexo.help.title') }}</x-dropdown-link>
                        <form method="POST" action="{{ config('nexo-sso.enabled') ? route('nexo-sso.logout') : route('logout') }}">
                            @csrf
                            <x-dropdown-link>{{ __('Sign out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </x-slot:actions>
        </x-nexo-header>

        {{-- Mobile primary nav: the header nav is hidden < md, so surface the
             three sections here for small screens. --}}
        <nav aria-label="{{ __('nexo.nav.primary') }}" class="flex gap-1 overflow-x-auto border-b border-line bg-surface px-3 py-2 md:hidden">
            @foreach ([['dashboard', __('Links')], ['analytics', __('Analytics')], ['design.edit', __('Design')]] as [$route, $label])
                <a href="{{ route($route) }}"
                   @if (request()->routeIs($route)) aria-current="page" @endif
                   @class([
                       'whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium',
                       'text-primary' => request()->routeIs($route),
                       'text-muted hover:text-ink' => ! request()->routeIs($route),
                   ])>{{ $label }}</a>
            @endforeach
        </nav>

        @isset($header)
            <header class="border-b border-line bg-surface">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main id="main" class="flex-1">
            {{ $slot }}
        </main>

        <x-nexo-footer />
    </body>
</html>
