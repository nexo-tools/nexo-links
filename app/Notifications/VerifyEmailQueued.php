<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Email verification, in this product's template and language.
 *
 * The framework default rendered Laravel's markdown wrapper. Its strings did
 * happen to be translated here, but the shell was the framework's, not the
 * product's: the one mail a new account receives looked like no other surface
 * of the tool that sent it.
 *
 * Queued for the family reason (C2): a slow relay must not turn a sign-up into
 * a failed request for an account that was in fact created.
 */
class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /** @param  mixed  $notifiable */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Verify your email'))
            ->view('emails.verify-email', ['url' => $this->verificationUrl($notifiable)]);
    }
}
