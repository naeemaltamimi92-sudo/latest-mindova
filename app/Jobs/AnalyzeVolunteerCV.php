<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Volunteer;
use App\Services\AI\CVAnalysisService;
use App\Services\Utilities\CVTextExtractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeVolunteerCV implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 600; // 10 minutes

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 900; // 15 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Volunteer $volunteer
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'analyze_cv_' . $this->volunteer->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->volunteer->id;
    }

    /**
     * Execute the job.
     */
    public function handle(
        CVTextExtractor $extractor,
        CVAnalysisService $analysisService
    ): void {
        $this->executeWithRobustHandling(function () use ($extractor, $analysisService) {
            // Refresh the model
            $this->volunteer->refresh();

            // Skip if already completed
            if ($this->volunteer->ai_analysis_status === 'completed') {
                Log::info('CV analysis already completed, skipping', [
                    'volunteer_id' => $this->volunteer->id,
                ]);
                return;
            }

            // Update status to processing
            $this->volunteer->update([
                'ai_analysis_status' => 'processing',
            ]);

            // Extract text from CV
            if (!$this->volunteer->cv_file_path) {
                throw new \Exception('No CV file path found for volunteer');
            }

            $cvText = $extractor->extract($this->volunteer->cv_file_path);

            // Validate extracted text
            if (!$extractor->validateExtractedText($cvText)) {
                throw new \Exception('Extracted CV text is too short or invalid');
            }

            Log::info('CV text extracted successfully', [
                'volunteer_id' => $this->volunteer->id,
                'text_length' => strlen($cvText),
            ]);

            // Analyze CV with AI
            $analysis = $analysisService->analyze($cvText, $this->volunteer);

            Log::info('CV analysis completed', [
                'volunteer_id' => $this->volunteer->id,
                'confidence_score' => $analysis['confidence_score'] ?? 0,
            ]);

            // Store results
            $analysisService->storeResults($this->volunteer, $analysis);

            Log::info('CV analysis results stored successfully', [
                'volunteer_id' => $this->volunteer->id,
            ]);

        }, ['volunteer_id' => $this->volunteer->id]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['volunteer_id' => $this->volunteer->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'volunteer_id' => $this->volunteer->id,
            'volunteer_name' => $this->volunteer->user->name ?? 'Unknown',
        ]);

        $this->volunteer->update([
            'ai_analysis_status' => 'failed',
            'validation_status' => 'failed',
            'validation_errors' => [
                'error' => $exception->getMessage(),
                'failed_permanently_at' => now()->toDateTimeString(),
                'attempts' => $this->attempts(),
            ],
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'volunteer:' . $this->volunteer->id,
            'cv_analysis',
        ];
    }
}
