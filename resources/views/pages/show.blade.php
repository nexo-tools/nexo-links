<!DOCTYPE html>
@php
    $accent = $page->themeAccent();
    $accentGradient = "background-image: linear-gradient(135deg, {$accent['from']}, {$accent['to']})";
    $customBg = $page->backgroundCss();
    $lightInk = $customBg !== null && ! $page->hasLightBackground();
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Deliberate exception to the "one <x-nexo-seo> per head" rule: this head
         is per-page, not per-tool. Title, description and og:image come from the
         owner's profile (their avatar is the OG card), og:type is `profile`
         rather than `website`, and there is no hreflang set because a storefront
         has no translated twin — it renders whatever the owner wrote, in one
         language. The shared component would flatten all of that. --}}
    <title>{{ '@'.$page->username }} · {{ config('app.name') }}</title>
    <meta name="description" content="{{ $page->bio ?? '@'.$page->username.' links' }}">
    <link rel="canonical" href="{{ route('page.show', $page->username) }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ '@'.$page->username }} · {{ config('app.name') }}">
    <meta property="og:description" content="{{ $page->bio ?? '@'.$page->username.' links' }}">
    <meta property="og:url" content="{{ route('page.show', $page->username) }}">
    @if ($page->avatar_path)
        <meta property="og:image" content="{{ url(Storage::url($page->avatar_path)) }}">
    @endif
    <meta name="twitter:card" content="summary">

    @include('partials.brand-head')

    @vite('resources/css/app.css')
</head>
<body @class([
        'min-h-screen antialiased',
        'bg-neutral-100 text-neutral-900 dark:bg-neutral-950 dark:text-neutral-50' => $customBg === null,
        'text-neutral-50' => $lightInk,
        'text-neutral-900' => $customBg !== null && ! $lightInk,
    ])
    style="{{ $customBg ? $customBg.'; ' : '' }}font-family: ui-sans-serif, system-ui, -apple-system, sans-serif">
    <main class="mx-auto flex min-h-screen w-full max-w-md flex-col px-5 pb-8 {{ $page->banner_path ? 'pt-5' : 'pt-14 sm:pt-20' }}">
        <header class="text-center">
            @if ($page->banner_path)
                <img src="{{ Storage::url($page->banner_path) }}" alt=""
                     class="h-32 w-full rounded-3xl object-cover shadow-sm sm:h-40">
                <div class="-mt-12">
            @endif

            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full p-1" style="{{ $accentGradient }}">
                @if ($page->avatar_path)
                    <img src="{{ Storage::url($page->avatar_path) }}" alt=""
                         class="h-full w-full rounded-full object-cover">
                @else
                    <div @class([
                        'flex h-full w-full items-center justify-center rounded-full text-3xl font-bold uppercase',
                        'bg-neutral-100 dark:bg-neutral-950' => $customBg === null,
                        'bg-neutral-900 text-neutral-50' => $lightInk,
                        'bg-white text-neutral-900' => $customBg !== null && ! $lightInk,
                    ])>
                        {{ mb_substr($page->username, 0, 1) }}
                    </div>
                @endif
            </div>

            @if ($page->banner_path)
                </div>
            @endif

            <h1 class="mt-4 text-2xl font-bold tracking-tight">{{ '@'.$page->username }}</h1>

            @if ($page->bio)
                <p @class([
                    'mx-auto mt-2 max-w-xs text-balance',
                    'text-neutral-600 dark:text-neutral-400' => $customBg === null,
                    'text-white/75' => $lightInk,
                    'text-neutral-700' => $customBg !== null && ! $lightInk,
                ])>{{ $page->bio }}</p>
            @endif
        </header>

        <ul class="mt-10 space-y-4">
            @foreach ($links as $link)
                <li>
                    @if ($link->isUpcoming())
                        <div data-countdown="{{ $link->starts_at->getTimestamp() }}"
                             @class([
                                 'rounded-2xl border border-dashed px-5 py-4 text-center',
                                 'border-neutral-300 bg-white/60 dark:border-neutral-700 dark:bg-neutral-900/60' => $customBg === null,
                                 'border-white/40 bg-white/10' => $lightInk,
                                 'border-neutral-400/60 bg-white/60' => $customBg !== null && ! $lightInk,
                             ])>
                            <p class="font-medium">{{ $link->title }}</p>
                            <p class="mt-1 text-sm tabular-nums opacity-70" aria-live="off">
                                {{ __('starts in') }} <span data-countdown-label>…</span>
                            </p>
                        </div>
                    @elseif ($link->is_highlighted)
                        <a href="{{ route('link.visit', $link) }}" data-highlighted rel="noopener"
                           style="{{ $accentGradient }}"
                           class="group relative block overflow-hidden rounded-2xl px-5 py-4 text-center font-semibold text-white shadow-lg transition duration-200 hover:-translate-y-0.5 hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-600 motion-reduce:transition-none">
                            <span class="absolute left-4 top-1/2 -mt-1 h-2 w-2 animate-pulse rounded-full bg-white/90 motion-reduce:animate-none" aria-hidden="true"></span>
                            {{ $link->title }}
                        </a>
                    @else
                        <a href="{{ route('link.visit', $link) }}" rel="noopener"
                           @class([
                               'block rounded-2xl px-5 py-4 text-center font-medium shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 motion-reduce:transition-none',
                               'border border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700 hover:border-neutral-300' => $customBg === null,
                               'bg-white text-neutral-900' => $customBg !== null,
                           ])>
                            {{ $link->title }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>

        @if ($links->isEmpty())
            <p class="mt-10 text-center opacity-60">{{ __('Nothing here yet.') }}</p>
        @endif

        @if ($page->socialLinks->isNotEmpty())
            <nav class="mt-8 flex flex-wrap justify-center gap-3" aria-label="{{ __('Social profiles') }}">
                @foreach ($page->socialLinks as $social)
                    <a href="{{ $social->url() }}" rel="noopener" target="_blank"
                       aria-label="{{ $social->label() }}" title="{{ $social->label() }}"
                       @class([
                           'flex h-11 w-11 items-center justify-center rounded-full transition hover:-translate-y-0.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 motion-reduce:transition-none',
                           'bg-white text-neutral-700 shadow-sm hover:text-neutral-900 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:text-neutral-50' => $customBg === null,
                           'bg-white/15 text-white hover:bg-white/25' => $lightInk,
                           'bg-white text-neutral-700 shadow-sm hover:text-neutral-900' => $customBg !== null && ! $lightInk,
                       ])>
                        <x-dynamic-component :component="'icons.'.$social->platform" class="h-5 w-5" />
                    </a>
                @endforeach
            </nav>
        @endif

        <footer @class([
            'mt-auto flex items-center justify-center gap-3 pt-14 text-center text-sm',
            'text-neutral-500 dark:text-neutral-400' => $customBg === null,
            'text-white/70' => $lightInk,
            'text-neutral-600' => $customBg !== null && ! $lightInk,
        ])>
            <a href="{{ config('nexo.attribution.url') }}" rel="noopener" class="transition hover:opacity-70">
                {{ config('nexo.attribution.label') }}
            </a>
            <span aria-hidden="true">·</span>
            <a href="{{ route('home') }}" class="transition hover:opacity-70">
                {{ __('Create yours') }}
            </a>
            <span aria-hidden="true">·</span>
            <x-language-switcher class="gap-1.5" />
            <span aria-hidden="true">·</span>
            {{-- Privacy only (not the whole legal pair): this page is where the
                 cookieless click measurement happens, so its visitors are the
                 people the policy is about. The owner-facing chrome carries both. --}}
            <a href="{{ route('legal.privacy') }}" class="transition hover:opacity-70">
                {{ __('Privacy') }}
            </a>
            <span aria-hidden="true">·</span>
            <a href="{{ route('report.create', $page->username) }}" rel="nofollow" class="transition hover:opacity-70">
                {{ __('Report') }}
            </a>
        </footer>
    </main>

    @if ($links->contains(fn ($link) => $link->isUpcoming()))
        {{-- STRICT CSP: this inline countdown is allow-listed by its exact sha256
             hash (App\Http\Middleware\SecurityHeaders + public/.htaccess, kept in
             sync) — no 'unsafe-inline'. Recompute the hash if you edit the script
             body below (whitespace included). Current hash:
             sha256-2qwONLNmvHJqxi/leywqDx1vrIZL78PIpOKJlXHdkRM= --}}
        <script>
            document.querySelectorAll('[data-countdown]').forEach((el) => {
                const target = Number(el.dataset.countdown) * 1000;
                const label = el.querySelector('[data-countdown-label]');
                let timer;

                const tick = () => {
                    const seconds = Math.floor((target - Date.now()) / 1000);

                    if (seconds <= 0) {
                        if (timer) clearInterval(timer);
                        window.location.reload();
                        return;
                    }

                    const pad = (n) => String(n).padStart(2, '0');
                    const days = Math.floor(seconds / 86400);
                    label.textContent = (days > 0 ? days + 'd ' : '')
                        + pad(Math.floor((seconds % 86400) / 3600))
                        + ':' + pad(Math.floor((seconds % 3600) / 60))
                        + ':' + pad(seconds % 60);
                };

                tick();
                timer = setInterval(tick, 1000);
            });
        </script>
    @endif
</body>
</html>
