<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>{{ __('Report this page') }} · {{ config('app.name') }}</title>

    @include('partials.brand-head')

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-bg font-sans text-ink antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-md flex-col px-5 py-14">
        <a href="{{ route('page.show', $page->username) }}" class="text-sm text-muted hover:text-ink">
            ← {{ '@'.$page->username }}
        </a>

        <h1 class="mt-4 text-2xl font-bold tracking-tight">{{ __('Report this page') }}</h1>

        @if (session('status') === 'report-sent')
            <div class="nexo-flash mt-8" role="status">
                {{ __('Thanks — your report was sent.') }}
            </div>
        @else
            <p class="mt-2 text-sm text-muted">
                {{ __('Something broken, misleading or abusive? Let the page owner know. Reports are anonymous.') }}
            </p>

            <form method="POST" action="{{ route('report.store', $page->username) }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="reason" class="block text-sm font-medium">{{ __('What\'s wrong?') }}</label>
                    <select id="reason" name="reason" required
                            class="mt-1 block w-full rounded-md border-control bg-bg text-ink shadow-sm focus:border-primary focus:ring-ring">
                        @foreach ($reasons as $value => $label)
                            <option value="{{ $value }}" @selected(old('reason') === $value)>{{ __($label) }}</option>
                        @endforeach
                    </select>
                    @error('reason')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="link_id" class="block text-sm font-medium">{{ __('Which link? (optional)') }}</label>
                    <select id="link_id" name="link_id"
                            class="mt-1 block w-full rounded-md border-control bg-bg text-ink shadow-sm focus:border-primary focus:ring-ring">
                        <option value="">{{ __('The whole page') }}</option>
                        @foreach ($links as $link)
                            <option value="{{ $link->id }}" @selected(old('link_id') == $link->id)>{{ $link->title }}</option>
                        @endforeach
                    </select>
                    @error('link_id')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="details" class="block text-sm font-medium">{{ __('Details (optional)') }}</label>
                    <textarea id="details" name="details" rows="3" maxlength="500"
                              class="mt-1 block w-full rounded-md border-control bg-bg text-ink shadow-sm focus:border-primary focus:ring-ring">{{ old('details') }}</textarea>
                    @error('details')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button class="nexo-btn nexo-btn--primary">
                    {{ __('Send report') }}
                </button>
            </form>
        @endif
    </main>
</body>
</html>
