<?php

use App\Mail\EmailChanged;
use App\Mail\NexoIdLinked;
use App\Mail\PageReported;
use App\Mail\PasswordChanged;
use App\Models\Page;
use App\Models\User;
use App\Notifications\VerifyEmailQueued;
use App\Services\NexoSso\NexoSsoUserResolver;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

/** The four notices this tool owed: report, email change, password, SSO link. */
it('tells the page owner when their page is reported', function () {
    Mail::fake();

    $page = Page::factory()->create();

    $this->post(route('report.store', $page->username), [
        'reason' => 'spam',
        'details' => 'Links to a scam shop.',
    ])->assertRedirect();

    // A report used to sit in the database until the owner happened to look.
    Mail::assertQueued(PageReported::class, fn (PageReported $mail): bool => $mail->hasTo($page->user->email));
});

it('warns the old address when the account email changes, and re-verifies the new one', function () {
    Mail::fake();
    Notification::fake();

    $user = User::factory()->create(['email' => 'old@example.com']);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => $user->name,
        'email' => 'new@example.com',
    ])->assertRedirect();

    // Changing the email is how an account is stolen for good: the only person
    // who can notice is the one who stops receiving.
    Mail::assertQueued(EmailChanged::class, fn (EmailChanged $mail): bool => $mail->hasTo('old@example.com'));
    Notification::assertSentTo($user->fresh(), VerifyEmailQueued::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('says nothing when the profile is saved without changing the email', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'same@example.com']);

    $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'A new display name',
        'email' => 'same@example.com',
    ])->assertRedirect();

    Mail::assertNothingQueued();
});

it('tells the owner when their password is reset', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'ana@example.com']);
    $token = Password::createToken($user);

    $this->post(route('password.store'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'a-brand-new-password',
        'password_confirmation' => 'a-brand-new-password',
    ])->assertRedirect();

    Mail::assertQueued(PasswordChanged::class, fn (PasswordChanged $mail): bool => $mail->hasTo('ana@example.com'));
});

it('tells the owner the first time Nexo ID is linked, and only then', function () {
    Mail::fake();

    User::factory()->create(['email' => 'ana@example.com']);
    $claims = ['sub' => 'sub-1', 'email' => 'ana@example.com', 'email_verified' => true, 'name' => 'Ana'];

    app(NexoSsoUserResolver::class)->resolve($claims);
    app(NexoSsoUserResolver::class)->resolve($claims);

    Mail::assertQueued(NexoIdLinked::class, fn (NexoIdLinked $mail): bool => $mail->hasTo('ana@example.com'));
    Mail::assertQueuedCount(1);
});
