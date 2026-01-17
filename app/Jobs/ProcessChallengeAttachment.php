<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\ChallengeAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
     *
     * PDFs are now sent directly to Claude AI, so we just verify the file exists
     * and mark the attachment as ready for processing.
     */
    public function handle(): void
    {
        $this->executeWithRobustHandling(function () {
            // Refresh the model
            $this->attachment->refresh();

            // Skip if already processed
            if ($this->attachment->processed) {
                Log::info('Attachment already processed, skipping', [
                    'attachment_id' => $this->attachment->id,
                ]);
                return;
            }

            // Verify file exists
            $fullPath = Storage::path($this->attachment->file_path);
            if (!file_exists($fullPath)) {
                throw new \Exception("File not found: {$this->attachment->file_path}");
            }

            // Mark as processed (PDF will be sent directly to AI, no text extraction needed)
            $this->attachment->update([
                'processed' => true,
                'processed_at' => now(),
            ]);

            Log::info('Attachment marked as ready for AI processing', [
                'attachment_id' => $this->attachment->id,
                'file_path' => $this->attachment->file_path,
            ]);

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
