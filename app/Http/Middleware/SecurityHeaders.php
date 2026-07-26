<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Skipped while the Vite dev server is running (assets come from
        // another origin in local development).
        // KEEP IN SYNC with public/.htaccess, which re-asserts this same
        // policy at the web-server level because some shared hosts replace
        // PHP-sent CSP headers with their own.
        if (! file_exists(public_path('hot'))) {
            $response->headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                // Alpine evaluates directive expressions at runtime, which needs
                // 'unsafe-eval' — but no 'unsafe-inline': the two inline <script>s are
                // allow-listed by their exact sha256 hash. First = the FOUC-free
                // theme-init (partials/theme-init); second = the countdown on public
                // pages (pages/show). Recompute a hash if you edit either snippet and
                // mirror the whole policy in public/.htaccess (a test keeps them in sync).
                "script-src 'self' 'unsafe-eval' 'sha256-QY4re+NFw+ChK0c8H/EaTpktoUisSWU0fL7V6J43umM=' 'sha256-2qwONLNmvHJqxi/leywqDx1vrIZL78PIpOKJlXHdkRM='",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: blob:",
                "font-src 'self'",
                // The one permitted external host: the Nexo Tools hub, so the
                // opt-in cookieless pageview beacon (navigator.sendBeacon) is not
                // blocked (there is no other connect-src, so it would fall back to
                // default-src 'self'). It only fires when the beacon metas render.
                "connect-src 'self' https://nexotools.alvarocdev.com",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                'upgrade-insecure-requests',
            ]));
        }

        return $response;
    }
}
