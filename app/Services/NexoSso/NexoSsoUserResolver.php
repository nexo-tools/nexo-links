<?php

declare(strict_types=1);

namespace App\Services\NexoSso;

use App\Mail\NexoIdLinked;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Maps verified OIDC claims to a local user. ADAPTATION POINT: newUser() is the
 * one method each tool tunes to its own users table (extra columns, defaults).
 */
class NexoSsoUserResolver
{
    /** @param  array<string, mixed>  $claims */
    public function resolve(array $claims): User
    {
        $sub = (string) ($claims['sub'] ?? '');
        if ($sub === '') {
            throw new NexoSsoException('Missing sub claim.');
        }

        // Returning user: match by stable sub, even if the email changed. (AC-LINK-3)
        $user = User::query()->where('nexo_id_sub', $sub)->first();
        if ($user !== null) {
            return $user;
        }

        $email = (string) ($claims['email'] ?? '');
        if ($email === '') {
            throw new NexoSsoException('Missing email claim (is the email scope granted?).');
        }

        // Existing local account: link only on a provider-verified email —
        // otherwise an attacker could claim someone else's account. (AC-LINK-2)
        $existing = User::query()->where('email', $email)->first();
        if ($existing !== null) {
            if (($claims['email_verified'] ?? false) !== true) {
                throw new NexoSsoLinkRefusedException('Email not verified by the identity provider.');
            }

            $firstLink = $existing->nexo_id_sub === null;

            $existing->forceFill(['nexo_id_sub' => $sub])->save();

            // The tool where the link happens is the one that tells the owner,
            // never nexo-id: this app knows which account was just connected.
            if ($firstLink) {
                Mail::to($existing->email)
                    ->locale(app()->getLocale())
                    ->queue(new NexoIdLinked($existing));
            }

            return $existing;
        }

        // First login, no local account: create one from the claims. (AC-LINK-1)
        $user = $this->newUser($claims);
        $user->forceFill(['nexo_id_sub' => $sub])->save();

        return $user;
    }

    /**
     * ADAPTATION POINT — align with this tool's users table.
     *
     * @param  array<string, mixed>  $claims
     */
    protected function newUser(array $claims): User
    {
        return DB::transaction(function () use ($claims): User {
            $user = new User;
            $user->forceFill([
                'name' => (string) ($claims['name'] ?? Str::before((string) $claims['email'], '@')),
                'email' => (string) $claims['email'],
                // Random local password: this account authenticates via SSO. If the
                // tool also offers local login, "forgot password" still works.
                'password' => Str::password(40),
                'email_verified_at' => ($claims['email_verified'] ?? false) === true ? now() : null,
            ]);
            $user->save();

            // nexolinks-specific: every user owns a mandatory 1:1 page (username +
            // workspace). Registration is the only other place that creates it, so
            // without provisioning it here every dashboard route 404s (pageOf() ->
            // abort 404). Same invariant, established atomically with the user.
            $user->page()->create([
                'username' => $this->generateUsername($claims),
            ]);

            return $user;
        });
    }

    /**
     * A unique, reserved-safe, Username-rule-valid handle derived from the claims.
     * SSO carries no username but pages.username is mandatory + unique, so derive
     * one from the display name (or the email local-part), then disambiguate.
     *
     * @param  array<string, mixed>  $claims
     */
    protected function generateUsername(array $claims): string
    {
        $source = (string) ($claims['name'] ?? '');
        if ($source === '') {
            $source = Str::before((string) ($claims['email'] ?? ''), '@');
        }

        $base = Str::of($source)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '-') // collapse any non-alnum run to one hyphen
            ->trim('-')
            ->substr(0, 24)                        // leave room for a "-xxxxx" suffix within max 30
            ->trim('-')
            ->value();

        if (strlen($base) < 3) {
            $base = 'user';
        }

        $reserved = (array) config('nexo.reserved_usernames', []);
        $candidate = $base;

        // Disambiguate against reserved names and existing pages with a short
        // lowercase-alnum suffix (keeps the Username-rule format valid).
        while (
            strlen($candidate) < 3
            || in_array($candidate, $reserved, true)
            || Page::query()->where('username', $candidate)->exists()
        ) {
            $candidate = $base.'-'.Str::lower(Str::random(5));
        }

        return $candidate;
    }
}
