<?php

namespace App\Notifications;

use App\Models\ChallengeComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HighQualityCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ChallengeComment $comment
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('community.challenge', $this->comment->challenge_id);

        return (new MailMessage)
            ->subject("High-Quality Comment on Your Challenge")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You've received a high-quality comment (AI score: {$this->comment->ai_score}/10) on your challenge:")
            ->line("**Challenge:** {$this->comment->challenge->title}")
            ->line("**Commenter:** {$this->comment->user->name}")
            ->line("**Comment Preview:**")
            ->line(substr($this->comment->content, 0, 200) . (strlen($this->comment->content) > 200 ? '...' : ''))
            ->action('View Full Comment', $url)
            ->line('High-quality contributions like this help refine your challenge and attract the right talent.');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'High-Quality Comment Received',
            'message' => "Received a high-quality comment (score {$this->comment->ai_score}/10) on your challenge: {$this->comment->challenge->title}",
            'action_url' => route('community.challenge', $this->comment->challenge_id),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'high_quality_comment',
            'comment_id' => $this->comment->id,
            'challenge_id' => $this->comment->challenge_id,
            'challenge_title' => $this->comment->challenge->title,
            'commenter_name' => $this->comment->user->name,
            'ai_score' => $this->comment->ai_score,
            'comment_preview' => substr($this->comment->content, 0, 100),
            'message' => "Received a high-quality comment (score {$this->comment->ai_score}/10) on your challenge: {$this->comment->challenge->title}",
        ];
    }
}
