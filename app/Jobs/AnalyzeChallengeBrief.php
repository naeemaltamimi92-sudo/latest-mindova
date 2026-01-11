<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Challenge;
use App\Services\AI\ChallengeBriefService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeChallengeBrief implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 300; // 5 minutes

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Challenge $challenge
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'analyze_brief_' . $this->challenge->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->challenge->id;
    }

    /**
     * Execute the job.
     */
    public function handle(ChallengeBriefService $briefService): void
    {
        $this->executeWithRobustHandling(function () use ($briefService) {
            // Refresh the model to get latest state
            $this->challenge->refresh();

            // Skip if already completed or rejected
            if (in_array($this->challenge->ai_analysis_status, ['completed'])) {
                Log::info('Challenge brief analysis already completed, skipping', [
                    'challenge_id' => $this->challenge->id,
                ]);
                return;
            }

            // Skip if rejected
            if ($this->challenge->status === 'rejected') {
                Log::info('Challenge already rejected, skipping analysis', [
                    'challenge_id' => $this->challenge->id,
                ]);
                return;
            }

            // Update status to show processing
            $this->challenge->update([
                'status' => 'analyzing',
                'ai_analysis_status' => 'processing',
            ]);

            // Analyze and refine the brief
            $analysis = $briefService->analyze($this->challenge);

            // Check if the challenge was rejected as invalid/incomprehensible
            if (isset($analysis['is_valid']) && $analysis['is_valid'] === false) {
                Log::info('Challenge rejected as invalid', [
                    'challenge_id' => $this->challenge->id,
                    'rejection_reason' => $analysis['rejection_reason'] ?? 'Unknown',
                ]);

                // Store results (this will mark the challenge as rejected)
                $briefService->storeResults($this->challenge, $analysis);

                // Do NOT continue to complexity evaluation for rejected challenges
                return;
            }

            Log::info('Brief analysis completed successfully', [
                'challenge_id' => $this->challenge->id,
                'confidence_score' => $analysis['confidence_score'] ?? 0,
                'score' => $analysis['score'] ?? 0,
            ]);

            // Store results
            $briefService->storeResults($this->challenge, $analysis);

            // Chain to complexity evaluation only for valid challenges
            // But skip if complexity evaluation already exists
            $hasComplexityEvaluation = $this->challenge->challengeAnalyses()
                ->where('stage', 'complexity')
                ->exists();

            if (!$hasComplexityEvaluation) {
                // Add a small delay to prevent overwhelming the API
                EvaluateChallengeComplexity::dispatch($this->challenge)
                    ->delay(now()->addSeconds(5));
            } else {
                Log::info('Complexity evaluation already exists, skipping dispatch', [
                    'challenge_id' => $this->challenge->id,
                ]);
            }

        }, ['challenge_id' => $this->challenge->id]);
    }

    /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['challenge_id' => $this->challenge->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'challenge_id' => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
        ]);

        // Update challenge status to allow retry
        $this->challenge->update([
            'status' => 'submitted',
            'ai_analysis_status' => 'failed',
        ]);

        // Store the error in the database for admin review
        try {
            $this->challenge->challengeAnalyses()->create([
                'stage' => 'brief',
                'validation_status' => 'failed',
                'requires_human_review' => true,
                'validation_errors' => [
                    'error' => $exception->getMessage(),
                    'failed_at' => now()->toDateTimeString(),
                    'attempts' => $this->attempts(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store analysis failure record', [
                'challenge_id' => $this->challenge->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'challenge:' . $this->challenge->id,
            'brief_analysis',
        ];
    }
}
