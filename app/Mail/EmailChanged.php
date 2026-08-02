<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Tells the OLD address that the account's email was changed.
 *
 * Gap 7 of the ecosystem audit, and the security half of it: changing the email
 * is how an account is stolen for good, and the only person who can notice is
 * the one who no longer receives anything. So this goes to the address that
 * just lost the account, never to the new one.
 */
class EmailChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $previousEmail,
        public readonly string $newEmail,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('The email of your account was changed'));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.email-changed');
    }
}
