<?php

use App\Models\Page;
use App\Models\User;

test('the landing page renders with CTAs for guests', function () {
    Page::factory()->create(['username' => config('nexo.example_username')]);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('Share all your links in one place.')
        ->assertSee(route('register'))
        ->assertSee(route('login'))
        ->assertSee('See a live example')
        ->assertSee(url('/'.config('nexo.example_username')));
});

test('the h1 in Spanish is the tagline from the registry', function () {
    // One claim per tool across every surface (design.md, "Copy"): the landing's
    // h1 is not a page-local headline, it is the registry's tagline. The suite
    // runs in English, so the equality is checked on the translation map — which
    // is also where nexo-doctor reads it.
    $es = json_decode((string) file_get_contents(lang_path('es.json')), true);

    expect($es['Share all your links in one place.'])
        ->toBe(config('nexo-ecosystem.tools.nexolinks.tagline'));
});

test('the example button is hidden when the example page does not exist', function () {
    $this->get('/')
        ->assertOk()
        ->assertDontSee('See a live example');
});

test('authenticated users see the dashboard link instead of register', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/')
        ->assertOk()
        ->assertSee(route('dashboard'))
        ->assertDontSee('Log in');
});

test('the landing links to the repository', function () {
    $this->get('/')->assertOk()->assertSee(config('nexo.repository_url'));
});

test('public pages link back home with a create-yours CTA', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('Create yours')
        ->assertSee(route('home'));
});
