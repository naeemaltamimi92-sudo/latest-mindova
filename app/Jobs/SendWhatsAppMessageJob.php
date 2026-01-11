<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\WhatsAppNotification;
use App\Services\WhatsAppCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $templateName;
    public array $variables;
    public ?int $notificationId;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 30;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        User $user,
        string $templateName,
        array $variables = [],
        ?int $notificationId = null
    ) {
        $this->user = $user;
        $this->templateName = $templateName;
        $this->variables = $variables;
        $this->notificationId = $notificationId;

        $this->onQueue(config('whatsapp.queue.queue_name', 'whatsapp'));
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppCloudService $whatsAppService): void
    {
        try {
            // Check if user still has WhatsApp enabled
            if (!$this->user->hasWhatsAppEnabled()) {
                $this->markNotificationAsSkipped('User has disabled WhatsApp notifications');
                return;
            }

            // Check if user has a WhatsApp number
            if (!$this->user->whatsapp_number) {
                $this->markNotificationAsSkipped('User has no WhatsApp number');
                return;
            }

            // Send the message
            $result = $whatsAppService->sendMessage($this->user, $this->templateName, $this->variables);

            if ($result['success'] ?? false) {
                $this->markNotificationAsSent($result['message_id'] ?? null);
                Log::channel('whatsapp')->info('WhatsApp message sent successfully', [
                    'user_id' => $this->user->id,
                    'template' => $this->templateName,
                    'message_id' => $result['message_id'] ?? null,
                ]);
            } else {
                throw new Exception($result['error'] ?? 'Unknown error sending message');
            }
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Failed to send WhatsApp message', [
                'user_id' => $this->user->id,
                'template' => $this->templateName,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // If this is the last attempt, mark as failed
            if ($this->attempts() >= $this->tries) {
                $this->markNotificationAsFailed($e->getMessage());
            }

            throw $e;
        }
    }

    /**
     * Mark the notification as sent.
     */
    protected function markNotificationAsSent(?string $messageId): void
    {
        if ($this->notificationId) {
            WhatsAppNotification::where('id', $this->notificationId)
                ->update([
                    'status' => 'sent',
                    'provider_message_id' => $messageId,
                    'sent_at' => now(),
                ]);
        }
    }

    /**
     * Mark the notification as failed.
     */
    protected function markNotificationAsFailed(string $error): void
    {
        if ($this->notificationId) {
            WhatsAppNotification::where('id', $this->notificationId)
                ->update([
                    'status' => 'failed',
                    'error_message' => $error,
                ]);
        }
    }

    /**
     * Mark the notification as skipped.
     */
    protected function markNotificationAsSkipped(string $reason): void
    {
        if ($this->notificationId) {
            WhatsAppNotification::where('id', $this->notificationId)
                ->update([
                    'status' => 'skipped',
                    'error_message' => $reason,
                ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        Log::channel('whatsapp')->error('WhatsApp job failed permanently', [
            'user_id' => $this->user->id,
            'template' => $this->templateName,
            'error' => $exception->getMessage(),
        ]);

        $this->markNotificationAsFailed($exception->getMessage());
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'whatsapp',
            'user:' . $this->user->id,
            'template:' . $this->templateName,
        ];
    }
}
