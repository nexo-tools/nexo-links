<?php

use Illuminate\Support\Facades\Process;

// Guardian: es/pt stay in sync with the English source strings. The generator's
// --check mode does the work — it fails both when a __() string has no
// translation and when a lang file on disk no longer matches what the generator
// produces (hand-edited, or the generator was never re-run).
it('generated translation files stay in sync with source strings', function () {
    $node = trim((string) (Process::run('command -v node')->output()));

    if ($node === '') {
        $this->markTestSkipped('node is not available in this environment (CI runs the generator step directly).');
    }

    $result = Process::path(base_path())->run('node scripts/generate-translations.mjs --check');

    expect($result->successful())->toBeTrue($result->errorOutput() ?: $result->output());
});

it('ships the three ecosystem locales, with pt as the canonical Portuguese code', function () {
    // `pt_BR` is the laravel-lang source, never the code the app exposes.
    expect(array_keys(config('nexo.locales')))->toBe(['en', 'es', 'pt']);

    foreach (['es', 'pt'] as $locale) {
        expect(is_file(lang_path("{$locale}.json")))->toBeTrue("lang/{$locale}.json is missing.");
    }

    expect(is_dir(lang_path('pt_BR')))->toBeFalse('pt_BR must not exist — the ecosystem code is pt.');
});
