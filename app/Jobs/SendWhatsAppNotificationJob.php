<?php

namespace App\Jobs;

use App\Models\WhatsAppNotification;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 180, 600]; // Retry after 1 min, 3 min, 10 min

    /**
     * Create a new job instance.
     */
    public function __construct(
        public WhatsAppNotification $notification
    ) {
        // Apply 1-2 minute delay (random between 60-120 seconds)
        $this->delay(now()->addSeconds(rand(60, 120)));
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        // Re-check all conditions before sending
        $user = $this->notification->user;

        // 1. Check if user still opted in
        if (!$user->hasWhatsAppEnabled()) {
            $this->notification->markAsSkipped('User has not opted in or phone number missing');
            Log::info('WhatsApp notification skipped: User not opted in', [
                'notification_id' => $this->notification->id,
                'user_id' => $user->id,
            ]);
            return;
        }

        // 2. Check if notification still queued (not already sent/failed/skipped)
        if ($this->notification->status !== 'queued') {
            Log::info('WhatsApp notification already processed', [
                'notification_id' => $this->notification->id,
                'status' => $this->notification->status,
            ]);
            return;
        }

        // 3. Verify entity still exists and is relevant
        if (!$this->isEntityStillRelevant()) {
            $this->notification->markAsSkipped('Entity no longer relevant or deleted');
            Log::info('WhatsApp notification skipped: Entity not relevant', [
                'notification_id' => $this->notification->id,
                'entity_type' => $this->notification->entity_type,
                'entity_id' => $this->notification->entity_id,
            ]);
            return;
        }

        try {
            // Prepare template variables based on notification type
            $variables = $this->prepareTemplateVariables();

            // Send WhatsApp message
            $providerMessageId = $whatsAppService->sendMessage(
                $user,
                $this->notification->template_name,
                $variables
            );

            // Mark as sent
            $this->notification->markAsSent($providerMessageId);

            Log::info('WhatsApp notification sent successfully', [
                'notification_id' => $this->notification->id,
                'user_id' => $user->id,
                'provider_message_id' => $providerMessageId,
            ]);

        } catch (Exception $e) {
            // Mark as failed
            $this->notification->markAsFailed($e->getMessage());

            Log::error('WhatsApp notification failed', [
                'notification_id' => $this->notification->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Check if the entity (team/task/challenge) still exists and is relevant.
     */
    private function isEntityStillRelevant(): bool
    {
        $entityClass = match($this->notification->entity_type) {
            'team' => \App\Models\Team::class,
            'task' => \App\Models\Task::class,
            'challenge' => \App\Models\Challenge::class,
            default => null,
        };

        if (!$entityClass) {
            return false;
        }

        return $entityClass::where('id', $this->notification->entity_id)->exists();
    }

    /**
     * Prepare template variables based on notification type.
     */
    private function prepareTemplateVariables(): array
    {
        return match($this->notification->type) {
            'team_invite' => $this->prepareTeamInviteVariables(),
            'task_assigned' => $this->prepareTaskAssignedVariables(),
            'critical_update' => $this->prepareCriticalUpdateVariables(),
            default => [],
        };
    }

    /**
     * Prepare variables for team invitation template.
     */
    private function prepareTeamInviteVariables(): array
    {
        $team = \App\Models\Team::find($this->notification->entity_id);

        if (!$team) {
            throw new Exception('Team not found');
        }

        $challenge = $team->challenge;

        return [
            'challenge_title' => $challenge->title ?? 'Unknown Challenge',
            'link' => url('/teams/' . $team->id),
        ];
    }

    /**
     * Prepare variables for task assigned template.
     */
    private function prepareTaskAssignedVariables(): array
    {
        $task = \App\Models\Task::find($this->notification->entity_id);

        if (!$task) {
            throw new Exception('Task not found');
        }

        return [
            'task_title' => $task->title ?? 'New Task',
            'link' => url('/tasks/' . $task->id),
        ];
    }

    /**
     * Prepare variables for critical update template.
     */
    private function prepareCriticalUpdateVariables(): array
    {
        // This would depend on how you store/track critical updates
        // For now, using challenge as the entity
        $challenge = \App\Models\Challenge::find($this->notification->entity_id);

        if (!$challenge) {
            throw new Exception('Challenge not found');
        }

        return [
            'challenge_title' => $challenge->title ?? 'Challenge',
            'update_message' => 'Please check the latest update.',
            'link' => url('/challenges/' . $challenge->id),
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        $this->notification->markAsFailed('Failed after ' . $this->tries . ' attempts: ' . $exception->getMessage());

        Log::error('WhatsApp notification permanently failed', [
            'notification_id' => $this->notification->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
