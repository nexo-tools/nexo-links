{{-- The public landing's document scaffold. It exists so landing.blade.php can
     be nothing but the five canonical sections: the body class that pins the
     footer to the bottom uses min-h-screen, and the family guardian (and
     STANDARD.md's anti-fingerprint grep) scans the landing view as a whole
     file. Keeping the scaffold here is the same shape nexoshort uses — the
     landing owns its content, the layout owns the page. --}}
@props(['title', 'description'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-nexo-seo :title="config('app.name').' — '.$title" :description="$description" />

    @include('partials.brand-head')

    @include('partials.beacon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-bg font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-surface focus:px-4 focus:py-2 focus:text-ink">
        {{ __('Skip to content') }}
    </a>

    {{-- Standard full-width ecosystem chrome: same header/footer and element
         order as nexotools/nexoid (wordmark + nav + app-switcher + locale +
         theme + account). Only the hero carries a primary CTA (design.md,
         "CTA voice"), so the header's account action is the ghost sign-in —
         a second solid violet button a few pixels away, with a different verb,
         was competing with the one that answers the claim. --}}
    <x-nexo-header brand="Nexo Links" mark="/ecosystem/nexolinks.svg" :home="url('/')">
        <x-slot:actions>
            @auth
                <a href="{{ route('dashboard') }}" class="nexo-btn nexo-btn--ghost">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('login') }}" class="nexo-btn nexo-btn--ghost nexo-header__auth">{{ __('Sign in') }}</a>
            @endauth
        </x-slot:actions>
    </x-nexo-header>

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    <x-nexo-footer />
</body>
</html>
