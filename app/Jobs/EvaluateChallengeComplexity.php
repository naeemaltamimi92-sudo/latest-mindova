<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Challenge;
use App\Services\AI\ComplexityEvaluationService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class EvaluateChallengeComplexity implements ShouldQueue, ShouldBeUnique
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
        return 'evaluate_complexity_' . $this->challenge->id;
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
    public function handle(
        ComplexityEvaluationService $complexityService,
        NotificationService $notificationService
    ): void {
        $this->executeWithRobustHandling(function () use ($complexityService, $notificationService) {
            // Refresh the model to get latest state
            $this->challenge->refresh();

            // Skip if already has a score or is rejected
            if ($this->challenge->status === 'rejected') {
                Log::info('Challenge rejected, skipping complexity evaluation', [
                    'challenge_id' => $this->challenge->id,
                ]);
                return;
            }

            // Check if brief analysis exists
            $briefAnalysis = $this->challenge->challengeAnalyses()
                ->where('stage', 'brief')
                ->latest()
                ->first();

            if (!$briefAnalysis) {
                Log::warning('No brief analysis found, re-dispatching brief analysis', [
                    'challenge_id' => $this->challenge->id,
                ]);

                // Re-dispatch brief analysis
                AnalyzeChallengeBrief::dispatch($this->challenge)
                    ->delay(now()->addSeconds(10));
                return;
            }

            // Check if complexity already evaluated
            $existingComplexity = $this->challenge->challengeAnalyses()
                ->where('stage', 'complexity')
                ->exists();

            if ($existingComplexity && $this->challenge->score) {
                Log::info('Complexity already evaluated, skipping', [
                    'challenge_id' => $this->challenge->id,
                    'score' => $this->challenge->score,
                ]);
                return;
            }

            // Evaluate complexity
            $evaluation = $complexityService->evaluate($this->challenge, $briefAnalysis);

            Log::info('Complexity evaluation completed', [
                'challenge_id' => $this->challenge->id,
                'complexity_level' => $evaluation['complexity_level'] ?? null,
                'approach' => $evaluation['recommended_approach'] ?? null,
            ]);

            // Store results
            $complexityService->storeResults($this->challenge, $evaluation);

            // Refresh challenge to get the latest score
            $this->challenge->refresh();

            // Decide next step based on complexity level (1-4 scale from ComplexityEvaluationService)
            // Complexity 1-2: Community discussion, Complexity 3-4: Team execution
            if ($this->challenge->complexity_level >= 3) {
                // Complexity 3-4: Proceed with task decomposition
                Log::info('Challenge proceeding to task decomposition', [
                    'challenge_id' => $this->challenge->id,
                    'complexity_level' => $this->challenge->complexity_level,
                ]);

                $this->challenge->update(['challenge_type' => 'team_execution']);

                // Only dispatch if tasks don't already exist
                if (!$this->challenge->tasks()->exists()) {
                    // Dispatch with delay to prevent API overload
                    DecomposeChallengeTasks::dispatch($this->challenge)
                        ->delay(now()->addSeconds(10));
                } else {
                    Log::info('Tasks already exist, skipping decomposition dispatch', [
                        'challenge_id' => $this->challenge->id,
                        'task_count' => $this->challenge->tasks()->count(),
                    ]);
                }
            } else {
                // Complexity 1-2: Move to community discussion
                Log::info('Challenge set for community discussion', [
                    'challenge_id' => $this->challenge->id,
                    'complexity_level' => $this->challenge->complexity_level,
                ]);

                $this->challenge->update([
                    'status' => 'active',
                    'challenge_type' => 'community_discussion',
                    'ai_analysis_status' => 'completed',
                    'ai_analyzed_at' => now(),
                ]);

                // Notify owner that challenge is ready for community input
                $notificationService->notifyChallengeAnalyzed($this->challenge);
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

        // Update challenge to allow manual review
        $this->challenge->update([
            'ai_analysis_status' => 'failed',
        ]);

        // Create a complexity analysis record with failure
        try {
            $this->challenge->challengeAnalyses()->create([
                'stage' => 'complexity',
                'validation_status' => 'failed',
                'requires_human_review' => true,
                'validation_errors' => [
                    'error' => $exception->getMessage(),
                    'failed_at' => now()->toDateTimeString(),
                    'attempts' => $this->attempts(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store complexity failure record', [
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
            'complexity_evaluation',
        ];
    }
}
