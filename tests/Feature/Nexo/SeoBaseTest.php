<?php

// Guardian: the public "outside" of the site (SEO/discovery) stays complete.
// The landing renders a full <x-nexo-seo> head (description/canonical/OG/Twitter
// + hreflang), and robots.txt / sitemap.xml keep serving with the right shape.

it('serves meta description, canonical, open graph and hreflang on the home page', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->toContain('<meta name="description"')
        ->toContain('<link rel="canonical" href="'.url('/').'"')
        ->toContain('<meta property="og:title"')
        ->toContain('<meta property="og:url" content="'.url('/').'"')
        ->toContain('<meta property="og:image"')
        ->toContain('hreflang="es"')
        ->toContain('hreflang="en"')
        ->toContain('hreflang="pt"')
        ->toContain('hreflang="x-default"')
        // The component's doc comment must stay a comment (no leaked literal props)
        // and prop values must be escaped exactly once (no double-encoded entities).
        ->not->toContain(':hreflang=')
        ->not->toContain(':noindex=')
        ->not->toContain('&amp;#0');
});

it('serves the same standard head on the other trilingual public pages', function () {
    // These pages exist in es/en/pt, so they need the hreflang set the shared
    // component emits — a hand-rolled <head> is how /help lost it once already.
    // The storefront (/{username}) is deliberately NOT here: it keeps its own
    // per-page head (see the comment in pages/show.blade.php).
    foreach ([route('help'), route('legal.privacy'), route('legal.terms')] as $url) {
        $html = $this->get($url)->assertOk()->getContent();

        expect($html)
            ->toContain('<link rel="canonical" href="'.$url.'"')
            ->toContain('<meta property="og:title"')
            ->toContain('<meta property="og:image"')
            ->toContain('hreflang="es"')
            ->toContain('hreflang="en"')
            ->toContain('hreflang="pt"')
            ->toContain('hreflang="x-default"');
    }
});

it('serves robots.txt with the private surface disallowed and a sitemap pointer', function () {
    $response = $this->get('/robots.txt');

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('text/plain');
    expect($response->getContent())
        ->toContain('User-agent: *')
        ->toContain('Disallow: /login')
        ->toContain('Disallow: /register')
        ->toContain('Disallow: /dashboard')
        ->toContain('Disallow: /profile')
        ->toContain('Sitemap: '.route('sitemap'));
});

it('serves a valid sitemap.xml listing the public home page', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('xml');
    expect($response->getContent())
        ->toContain('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">')
        ->toContain('<loc>'.url('/').'</loc>');

    expect(simplexml_load_string($response->getContent()))->not->toBeFalse();
});

it('emits JSON-LD that actually parses', function () {
    // The block used to render compiled Blade internals instead of JSON: keys
    // like `@context` are Blade directives (Laravel 11 added `@context`), so the
    // template was compiling them away and shipping broken structured data on
    // every page. Asserting the tag exists is not enough — it has to parse.
    $html = $this->get('/')->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    expect($matches[1])->not->toBeEmpty('No JSON-LD block was rendered.');

    foreach ($matches[1] as $block) {
        $decoded = json_decode($block, true);
        expect(json_last_error())->toBe(JSON_ERROR_NONE, 'JSON-LD is not valid JSON: '.substr($block, 0, 200));
        expect($decoded['@context'] ?? null)->toBe('https://schema.org');
        expect($decoded['@type'] ?? null)->not->toBeNull('JSON-LD has no @type.');
    }
});
