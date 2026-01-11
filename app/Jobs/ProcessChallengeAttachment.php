<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\ChallengeAttachment;
use App\Services\AttachmentProcessingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessChallengeAttachment implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 600; // 10 minutes for large file processing

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 900; // 15 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ChallengeAttachment $attachment
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'process_attachment_' . $this->attachment->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->attachment->id;
    }

    /**
     * Execute the job.
     */
    public function handle(AttachmentProcessingService $processingService): void
    {
        $this->executeWithRobustHandling(function () use ($processingService) {
            // Refresh the model
            $this->attachment->refresh();

            // Skip if already processed
            if ($this->attachment->processed) {
                Log::info('Attachment already processed, skipping', [
                    'attachment_id' => $this->attachment->id,
                ]);
                return;
            }

            // Extract text from the attachment
            $extractedText = $processingService->extractText($this->attachment);

            if ($extractedText) {
                Log::info('Attachment processed successfully', [
                    'attachment_id' => $this->attachment->id,
                    'text_length' => strlen($extractedText),
                ]);
            } else {
                Log::warning('No text extracted from attachment', [
                    'attachment_id' => $this->attachment->id,
                ]);

                // Mark as processed even if no text (e.g., for images)
                $this->attachment->markAsProcessed();
            }

        }, [
            'attachment_id' => $this->attachment->id,
            'challenge_id' => $this->attachment->challenge_id,
            'file_type' => $this->attachment->file_type,
        ]);
    }

    /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['attachment_id' => $this->attachment->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'attachment_id' => $this->attachment->id,
            'challenge_id' => $this->attachment->challenge_id,
        ]);

        // Mark as failed processing (still keep the attachment)
        $this->attachment->update([
            'processed' => false,
            'extracted_text' => 'Failed to process attachment: ' . $exception->getMessage(),
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'attachment:' . $this->attachment->id,
            'challenge:' . $this->attachment->challenge_id,
            'attachment_processing',
        ];
    }
}
