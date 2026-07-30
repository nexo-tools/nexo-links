<?php

use App\Models\Page;
use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('the account menu logs out by submitting, not by following a link', function () {
    // The menu item used to be an <a href="/logout"> with @click.prevent. The
    // route is POST-only, so without JS it navigated by GET and returned 405 —
    // and an <a> inside the <form> is invalid HTML either way. This is invisible
    // in a browser with Alpine working, hence the guard.
    $page = Page::factory()->create();

    $html = $this->actingAs($page->user)->get('/dashboard')->assertOk()->getContent();

    expect($html)->not->toContain('href="'.route('logout').'"');

    // And following such a link would not have logged anyone out anyway.
    $this->actingAs($page->user)->get('/logout');
    $this->assertAuthenticated();
});
