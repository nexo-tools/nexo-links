<?php

use App\Models\Page;

// The cookieless beacon emitter is wired into the owner-facing chrome (landing,
// help, dashboard, auth). It fires only when this instance opts in
// (NEXO_BEACON_ENABLED) and respects Do Not Track. Public link-in-bio pages
// stay beacon-free — visitors of a page owner are never measured by the hub.

test('the landing renders the beacon metas only when the beacon is enabled', function () {
    config(['nexo.beacon.enabled' => true]);

    $this->get('/')
        ->assertSee('name="nexo:beacon-endpoint"', false)
        ->assertSee('name="nexo:beacon-origin" content="nexolinks"', false);
});

test('the owner dashboard renders the beacon metas when enabled', function () {
    config(['nexo.beacon.enabled' => true]);
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')
        ->assertSee('name="nexo:beacon-origin" content="nexolinks"', false);
});

test('no beacon metas render when the beacon is off (default/standalone)', function () {
    config(['nexo.beacon.enabled' => false]);

    $this->get('/')
        ->assertDontSee('nexo:beacon-endpoint', false)
        ->assertDontSee('nexo:beacon-origin', false);
});

test('public link-in-bio pages never emit the beacon, even when enabled', function () {
    config(['nexo.beacon.enabled' => true]);
    $page = Page::factory()->create();

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertDontSee('nexo:beacon-endpoint', false)
        ->assertDontSee('nexo:beacon-origin', false);
});

test('the shareable snippet ships in the app bundle and honours Do Not Track', function () {
    $source = file_get_contents(resource_path('js/nexo-beacon.js'));

    expect($source)
        ->toContain('doNotTrack')
        ->toContain('globalPrivacyControl')
        ->toContain('sendBeacon')
        ->toContain("event: 'pageview'");

    expect(file_get_contents(resource_path('js/app.js')))->toContain('nexo-beacon.js');
});
