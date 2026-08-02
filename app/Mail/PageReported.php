<?php

namespace App\Mail;

use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Tells the owner that their page was reported.
 *
 * Gap 6 of the ecosystem audit: a report was written to the database and the
 * owner found out only if they happened to open /reports. Somebody flagging a
 * page is the kind of thing its owner should hear about the same day — either
 * to fix it, or because it is the first sign that someone is targeting them.
 *
 * The reason is included but the reporter is not: reports can be filed by
 * anyone, and handing the owner an identity would turn a moderation tool into
 * a retaliation tool.
 */
class PageReported extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Page $page,
        public readonly string $reason,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('Your page was reported'));
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.page-reported',
            with: ['reportsUrl' => route('reports.index')],
        );
    }
}
