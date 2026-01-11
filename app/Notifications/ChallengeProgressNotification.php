<?php

namespace App\Notifications;

use App\Models\Challenge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChallengeProgressNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Challenge $challenge,
        public array $progressData
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
        $progressPercent = $this->progressData['progress_percent'];
        $isComplete = $this->progressData['is_complete'];
        $url = route('challenges.show', $this->challenge->id);

        $mail = (new MailMessage)
            ->greeting("Hello {$notifiable->name}!");

        if ($isComplete) {
            $mail->subject("Challenge Completed: {$this->challenge->title}")
                ->line("Congratulations! Your challenge has been completed.")
                ->line("**Challenge:** {$this->challenge->title}")
                ->line("**Tasks Completed:** {$this->progressData['completed_tasks']}/{$this->progressData['total_tasks']}")
                ->line("**Average Quality Score:** {$this->progressData['quality_score']}/10")
                ->action('View Challenge Results', $url)
                ->line('Thank you for using Mindova to connect with talented volunteers!');
        } else {
            $mail->subject("Progress Update: {$this->challenge->title}")
                ->line("Your challenge has made progress.")
                ->line("**Challenge:** {$this->challenge->title}")
                ->line("**Progress:** {$progressPercent}% complete")
                ->line("**Tasks Completed:** {$this->progressData['completed_tasks']} out of {$this->progressData['total_tasks']}")
                ->action('View Challenge Details', $url)
                ->line('Keep up the momentum!');
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $progressPercent = $this->progressData['progress_percent'];
        $isComplete = $this->progressData['is_complete'];

        $message = $isComplete
            ? "Your challenge '{$this->challenge->title}' has been completed! All tasks are finished with an average quality score of {$this->progressData['quality_score']}/10."
            : "Progress update for '{$this->challenge->title}': {$progressPercent}% complete ({$this->progressData['completed_tasks']}/{$this->progressData['total_tasks']} tasks).";

        return [
            'type' => 'challenge_progress',
            'challenge_id' => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
            'progress_percent' => $progressPercent,
            'completed_tasks' => $this->progressData['completed_tasks'],
            'total_tasks' => $this->progressData['total_tasks'],
            'quality_score' => $this->progressData['quality_score'] ?? null,
            'is_complete' => $isComplete,
            'message' => $message,
        ];
    }
}
