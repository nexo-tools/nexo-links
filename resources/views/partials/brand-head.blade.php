{{-- Stamp <html data-theme> before first paint (no FOUC). Must precede the
     stylesheet; every full-page <head> pulls this partial in before @vite. --}}
@include('partials.theme-init')

{{-- @vite builds the woff2 files but never asks for them: the @font-face rules
     only ship if Vite::fonts() emits them. Without this line the family face is
     published and nobody requests it, and every page falls back to the system
     stack. It goes before @vite so the face is known when the CSS lands. --}}
{{ Vite::fonts() }}

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
{{-- Match the page background per scheme, not the accent: the browser chrome
     should extend the page, not paint a violet bar over a dark UI.
     Values are --nexo-bg (slate-50 / slate-950); a <meta> content can't reference
     a CSS var, so these literals are expected here (brand-head is on the
     guardian's allow-list). This partial is in every <head>, so it is the single
     source for theme-color — <x-nexo-seo> deliberately omits it. --}}
<meta name="theme-color" content="#f8fafc" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#020617" media="(prefers-color-scheme: dark)">
