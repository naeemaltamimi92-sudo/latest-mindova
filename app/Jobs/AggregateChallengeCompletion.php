<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Challenge;
use App\Models\WorkSubmission;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AggregateChallengeCompletion implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 180; // 3 minutes

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 300; // 5 minutes

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
        return 'aggregate_completion_' . $this->challenge->id;
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
    public function handle(NotificationService $notificationService): void
    {
        $this->executeWithRobustHandling(function () use ($notificationService) {
            // Refresh the model
            $this->challenge->refresh();

            // Skip if already completed
            if ($this->challenge->status === 'completed' && $this->challenge->aggregated_solutions) {
                Log::info('Challenge already aggregated, skipping', [
                    'challenge_id' => $this->challenge->id,
                ]);
                return;
            }

            // Get all tasks for this challenge
            $tasks = $this->challenge->tasks;

            // Collect all approved solutions
            $aggregatedSolutions = [];
            $totalQualityScore = 0;
            $submissionCount = 0;

            foreach ($tasks as $task) {
                // Get the best approved solution for each task (by company review)
                $bestSubmission = WorkSubmission::where('task_id', $task->id)
                    ->where('status', 'approved')
                    ->orderBy('ai_quality_score', 'desc')
                    ->first();

                if ($bestSubmission) {
                    $aggregatedSolutions[] = [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'task_description' => $task->description,
                        'volunteer_name' => $bestSubmission->volunteer->user->name ?? 'Unknown',
                        'volunteer_id' => $bestSubmission->volunteer_id,
                        'solution_description' => $bestSubmission->description,
                        'deliverable_url' => $bestSubmission->deliverable_url,
                        'attachments' => $bestSubmission->attachments,
                        'hours_worked' => $bestSubmission->hours_worked,
                        'quality_score' => $bestSubmission->ai_quality_score,
                        'ai_feedback' => $bestSubmission->ai_feedback,
                        'submitted_at' => $bestSubmission->submitted_at,
                    ];

                    $totalQualityScore += $bestSubmission->ai_quality_score;
                    $submissionCount++;
                }
            }

            // Calculate average quality score
            $averageQualityScore = $submissionCount > 0 ? round($totalQualityScore / $submissionCount, 2) : 0;

            // Store aggregated data
            $this->challenge->update([
                'aggregated_solutions' => $aggregatedSolutions,
                'average_solution_quality' => $averageQualityScore,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            Log::info('Challenge aggregation completed', [
                'challenge_id' => $this->challenge->id,
                'solutions_count' => $submissionCount,
                'average_quality' => $averageQualityScore,
            ]);

            // Notify challenge owner (company)
            try {
                $notificationService->notifyChallengeCompleted(
                    $this->challenge,
                    $aggregatedSolutions,
                    $averageQualityScore
                );
            } catch (\Exception $e) {
                Log::error('Failed to send challenge completed notification', [
                    'challenge_id' => $this->challenge->id,
                    'error' => $e->getMessage(),
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

        // Challenge remains in its current state
        // Admin can manually aggregate or retry
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'challenge:' . $this->challenge->id,
            'aggregation',
        ];
    }
}
