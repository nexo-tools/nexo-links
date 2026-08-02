<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sign-in attempts, per IP. The LoginRequest already locks out an
        // email+IP pair after five tries, which does nothing against somebody
        // spraying one password across many addresses from one machine — that
        // is what this catches. Env-tunable, like every named limit in the
        // family (templates/nexo-ui/STANDARD.md, "Rate limiting").
        RateLimiter::for('login-ip', fn (Request $request) => Limit::perMinute(
            (int) config('nexo.login_rate.per_ip')
        )->by('login-ip:'.$request->ip()));

        // The family mail layout lives under resources/views/emails/ rather than
        // resources/views/components/ because that is where hex literals are
        // allowed (NoHardcodedColorsTest) — and a mail needs them: clients strip
        // <style> and know nothing about the design tokens. This line gives it
        // the normal component syntax: <x-nexo-mail::layout>.
        Blade::anonymousComponentPath(resource_path('views/emails/nexo'), 'nexo-mail');
    }
}
