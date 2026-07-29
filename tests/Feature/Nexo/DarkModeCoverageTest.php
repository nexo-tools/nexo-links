<?php

// Guardian: dark mode is a first-class theme, not an afterthought on the home page.
//
// Two things are checked, because they fail in different ways:
//   1. The theme plumbing: <html> gets data-theme before paint, and the tokens
//      file declares both palettes. Without this the page flashes the wrong theme.
//   2. Coverage: the views do not paint surfaces with theme-blind utilities.
//      A view that hardcodes `bg-white` looks fine in light and breaks in dark —
//      exactly the drift NoHardcodedColorsTest cannot see (it only scans hex).
//
// `$themeBlind` lists utilities that bypass the token layer; extend it if the
// tool adopts more.
//
// Pest note: toContain() is variadic — a second argument is another needle, not
// a failure message — so human-readable messages go through toBeTrue()/toBe().

use RecursiveDirectoryIterator as Dir;
use RecursiveIteratorIterator as Walk;

it('stamps the theme before paint and ships both palettes', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect(str_contains($html, 'data-theme'))->toBeTrue('The theme-init snippet must stamp data-theme on <html> before paint.');

    $tokens = resource_path('css/nexo-tokens.css');
    expect(is_file($tokens))->toBeTrue('nexo-tokens.css is missing — the tool is not on the brand tokens.');

    $css = (string) file_get_contents($tokens);
    expect(str_contains($css, '--nexo-'))->toBeTrue('Token variables missing from nexo-tokens.css.');
    expect(str_contains($css, 'dark'))->toBeTrue('nexo-tokens.css declares no dark palette.');
});

it('paints every view through the token layer, so they work in both themes', function () {
    // Utilities that hardcode a light-only surface, bypassing the tokens.
    $themeBlind = ['bg-white', 'bg-gray-50', 'bg-gray-100', 'text-black'];

    // The whole view tree, not a hand-picked list of key pages: the surface that
    // rots in dark mode is always the one nobody thought to put on the list.
    //
    // EXCEPTION — the public page (pages/show.blade.php). It is a storefront the
    // owner themes: a preset accent plus an optional solid/gradient background of
    // their choosing. When a custom background is set the page is NOT in the
    // visitor's theme at all, so ink and cards are picked from the background's
    // own brightness (Page::hasLightBackground) rather than from data-theme —
    // `bg-white` there is a card on the owner's colour, and pairing it with a
    // `dark:` variant would make the tool's theme override the owner's design.
    // The default (no custom background) branch of that view IS theme-paired.
    $storefront = str_replace('\\', '/', resource_path('views/pages/show.blade.php'));

    $offenders = [];

    /** @var SplFileInfo $file */
    foreach (new Walk(new Dir(resource_path('views'), FilesystemIterator::SKIP_DOTS)) as $file) {
        if (! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }

        if (str_replace('\\', '/', $file->getPathname()) === $storefront) {
            continue;
        }

        $contents = (string) file_get_contents($file->getPathname());

        foreach ($themeBlind as $utility) {
            // Allowed when paired with a dark: variant on the same element, so a
            // surface that must stay light in both themes says so out loud. The
            // dark: variant of the utility itself (`dark:bg-white`, deliberate
            // white in both themes) is not an offender, hence the lookbehind —
            // without it the very fix the message asks for fails the test.
            if (preg_match('/(?<!dark:)\b'.preg_quote($utility, '/').'\b(?![^"\']*dark:)/', $contents)) {
                $offenders[] = $file->getPathname().' -> '.$utility;
            }
        }
    }

    expect($offenders)->toBe([], "Theme-blind utilities found (use token classes like bg-bg/bg-surface, or pair with dark:):\n".implode("\n", $offenders));
});

it('keeps the storefront readable on its own theme, not the visitor theme', function () {
    // The carve-out above is only legitimate while the storefront really does
    // pick its ink from the owner's background. If that logic disappears, the
    // exception stops being one and this fails.
    $view = (string) file_get_contents(resource_path('views/pages/show.blade.php'));

    expect(str_contains($view, 'hasLightBackground'))->toBeTrue('The storefront no longer derives its ink from the owner background — it must go back under the token layer.');
    // Without a custom background it falls back to the visitor's theme.
    expect(str_contains($view, 'dark:bg-neutral-950'))->toBeTrue('The storefront default background is no longer theme-paired.');
});
