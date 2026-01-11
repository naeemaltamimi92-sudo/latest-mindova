<?php

namespace App\Notifications;

use App\Models\WorkSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SolutionRevisionRequiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public WorkSubmission $submission,
        public array $analysis
    ) {}

    /**
     * Get the notification's delivery channels.
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
        $task = $this->submission->task;
        $qualityScore = $this->analysis['quality_score'];

        return (new MailMessage)
            ->subject('Solution Revision Required')
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your submitted solution for the task \"{$task->title}\" requires revision.")
            ->line("**AI Quality Score:** {$qualityScore}/100")
            ->line("**Feedback:** " . ($this->analysis['feedback'] ?? 'Please review the task requirements carefully and improve your solution.'))
            ->action('View Task & Resubmit', route('tasks.show', $task->id))
            ->line('Please study the task requirements again and submit an improved solution.')
            ->line('Thank you for your contribution!');
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'solution_revision_required',
            'submission_id' => $this->submission->id,
            'task_id' => $this->submission->task_id,
            'task_title' => $this->submission->task->title,
            'quality_score' => $this->analysis['quality_score'],
            'feedback' => $this->analysis['feedback'] ?? 'Solution needs improvement',
            'message' => "Your solution for \"{$this->submission->task->title}\" requires revision (Score: {$this->analysis['quality_score']}/100)",
        ];
    }
}
