<?php

namespace App\Mail;

use App\Models\BugReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class BugReportSubmitted extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public BugReport $bugReport
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $priority = $this->bugReport->blocked_user ? '[CRITICAL] ' : '';

        return new Envelope(
            subject: $priority . 'Bug Report: ' . $this->bugReport->getIssueTypeLabel(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bug-report-submitted',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach screenshot if exists
        if ($this->bugReport->screenshot) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->bugReport->screenshot)
                ->as('screenshot.' . pathinfo($this->bugReport->screenshot, PATHINFO_EXTENSION))
                ->withMime('image/' . pathinfo($this->bugReport->screenshot, PATHINFO_EXTENSION));
        }

        return $attachments;
    }
}
