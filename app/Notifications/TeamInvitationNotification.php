<?php

namespace App\Notifications;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Team $team,
        public TeamMember $teamMember
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'team_invitation',
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'challenge_id' => $this->team->challenge_id,
            'challenge_title' => $this->team->challenge->title,
            'role' => $this->teamMember->role,
            'role_description' => $this->teamMember->role_description,
            'is_leader' => $this->teamMember->role === 'leader',
            'message' => $this->teamMember->role === 'leader'
                ? "You've been selected as the leader of {$this->team->name}!"
                : "You've been invited to join {$this->team->name} as a {$this->teamMember->role}.",
        ];
    }
}
