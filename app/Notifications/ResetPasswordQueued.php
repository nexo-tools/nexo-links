<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Password reset, in this product's template — same reason as
 * {@see VerifyEmailQueued}. Queued too (family rule C2): a slow relay must not
 * turn a reset request into a failed one for a link that was in fact created.
 */
class ResetPasswordQueued extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /** @param  mixed  $notifiable */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Reset your password'))
            ->view('emails.reset-password', [
                'url' => url(route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)),
                'expiresIn' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]);
    }
}
