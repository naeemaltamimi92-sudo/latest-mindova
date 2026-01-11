<?php

namespace App\Mail;

use App\Models\MindovaTeamMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MindovaTeamInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public MindovaTeamMember $teamMember;
    public string $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(MindovaTeamMember $teamMember, string $temporaryPassword)
    {
        $this->teamMember = $teamMember;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('You have been invited to join the Mindova Team'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mindova-team-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
