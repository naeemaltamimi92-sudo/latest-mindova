<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Challenge;
use App\Services\AI\TaskDecompositionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class DecomposeChallengeTasks implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 600; // 10 minutes for complex decomposition

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 900; // 15 minutes

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
        return 'decompose_tasks_' . $this->challenge->id;
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
    public function handle(TaskDecompositionService $decompositionService): void
    {
        $this->executeWithRobustHandling(function () use ($decompositionService) {
            // Refresh the model to get latest state
            $this->challenge->refresh();

            // Skip if already has tasks
            if ($this->challenge->tasks()->exists()) {
                Log::info('Challenge already has tasks, skipping decomposition', [
                    'challenge_id' => $this->challenge->id,
                    'task_count' => $this->challenge->tasks()->count(),
                ]);
                // Do NOT dispatch next job here - if tasks exist, matching was likely already done
                // or will be triggered by other means. This prevents cascade of unnecessary jobs.
                return;
            }

            // Get the previous analyses
            $briefAnalysis = $this->challenge->challengeAnalyses()
                ->where('stage', 'brief')
                ->latest()
                ->first();

            $complexityAnalysis = $this->challenge->challengeAnalyses()
                ->where('stage', 'complexity')
                ->latest()
                ->first();

            if (!$briefAnalysis) {
                Log::warning('No brief analysis found, re-dispatching brief analysis', [
                    'challenge_id' => $this->challenge->id,
                ]);
                AnalyzeChallengeBrief::dispatch($this->challenge)
                    ->delay(now()->addSeconds(10));
                return;
            }

            if (!$complexityAnalysis) {
                Log::warning('No complexity analysis found, re-dispatching complexity evaluation', [
                    'challenge_id' => $this->challenge->id,
                ]);
                EvaluateChallengeComplexity::dispatch($this->challenge)
                    ->delay(now()->addSeconds(10));
                return;
            }

            // Decompose into workstreams and tasks
            $decomposition = $decompositionService->decompose(
                $this->challenge,
                $briefAnalysis,
                $complexityAnalysis
            );

            $workstreamCount = count($decomposition['workstreams'] ?? []);
            $totalTasks = array_sum(array_map(
                fn($ws) => count($ws['tasks'] ?? []),
                $decomposition['workstreams'] ?? []
            ));

            Log::info('Task decomposition completed', [
                'challenge_id' => $this->challenge->id,
                'workstream_count' => $workstreamCount,
                'total_tasks' => $totalTasks,
            ]);

            // Store results (creates workstreams and tasks)
            $decompositionService->storeResults($this->challenge, $decomposition);

            // Update challenge status
            $this->challenge->update([
                'status' => 'active',
                'ai_analysis_status' => 'completed',
                'ai_analyzed_at' => now(),
            ]);

            Log::info('Challenge is now active and ready for volunteer matching', [
                'challenge_id' => $this->challenge->id,
            ]);

            // Dispatch volunteer matching with delay
            MatchVolunteersToTasks::dispatch($this->challenge)
                ->delay(now()->addSeconds(10));

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

        // Mark challenge as needing review
        $this->challenge->update([
            'status' => 'analyzing',
            'ai_analysis_status' => 'failed',
        ]);

        // Create an analysis record with failure
        try {
            $this->challenge->challengeAnalyses()->create([
                'stage' => 'decomposition',
                'validation_status' => 'failed',
                'requires_human_review' => true,
                'validation_errors' => [
                    'error' => $exception->getMessage(),
                    'failed_at' => now()->toDateTimeString(),
                    'attempts' => $this->attempts(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store decomposition failure record', [
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
            'task_decomposition',
        ];
    }
}
