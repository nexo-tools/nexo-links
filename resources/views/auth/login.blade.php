<x-guest-layout :title="__('Sign in to your account')">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-control text-primary shadow-sm focus:ring-ring" name="remember">
                <span class="ms-2 text-sm text-muted">{{ __('Remember me') }}</span>
            </label>
        </div>

        <x-primary-button class="mt-4 w-full">
            {{ __('Sign in') }}
        </x-primary-button>
    </form>

    @if (Route::has('password.request'))
        <div class="mt-4 text-center">
            <a class="underline text-sm text-muted hover:text-ink rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        </div>
    @endif

    @if (config('nexo-sso.enabled'))
        <div class="mt-6">
            <div class="relative flex items-center">
                <div class="flex-grow border-t border-control"></div>
                <span class="mx-3 text-xs uppercase text-muted">{{ __('or') }}</span>
                <div class="flex-grow border-t border-control"></div>
            </div>

            <x-input-error :messages="$errors->get('nexo_sso')" class="mt-4" />

            <a href="{{ route('nexo-sso.redirect') }}" class="mt-4 nexo-btn nexo-btn--ghost w-full">
                {{ __('Continue with Nexo ID') }}
            </a>
        </div>
    @endif

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-muted">
            {{ __("Don't have an account?") }}
            <a class="underline hover:text-ink rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring" href="{{ route('register') }}">
                {{ __('Create account') }}
            </a>
        </p>
    @endif
</x-guest-layout>
