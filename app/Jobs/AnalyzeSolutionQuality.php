<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\ReputationHistory;
use App\Models\TaskAssignment;
use App\Models\WorkSubmission;
use App\Notifications\SolutionRevisionRequiredNotification;
use App\Services\AI\SolutionScoringService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeSolutionQuality implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 300; // 5 minutes for solution analysis

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public WorkSubmission $submission
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'analyze_solution_' . $this->submission->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->submission->id;
    }

    /**
     * Execute the job.
     */
    public function handle(SolutionScoringService $scoringService): void
    {
        $this->executeWithRobustHandling(function () use ($scoringService) {
            // Refresh the model
            $this->submission->refresh();

            // Skip if already completed
            if ($this->submission->ai_analysis_status === 'completed') {
                Log::info('Solution analysis already completed, skipping', [
                    'submission_id' => $this->submission->id,
                ]);
                return;
            }

            // Update status
            $this->submission->update(['ai_analysis_status' => 'processing']);

            // Analyze solution quality
            $analysis = $scoringService->analyzeSolution($this->submission);

            Log::info('Solution analysis completed', [
                'submission_id' => $this->submission->id,
                'quality_score' => $analysis['quality_score'] ?? 0,
                'solves_task' => $analysis['solves_task'] ?? false,
            ]);

            // Store results
            $scoringService->storeResults($this->submission, $analysis);

            // Handle low-score solutions - require resubmission (70% threshold)
            if (!($analysis['solves_task'] ?? false) || ($analysis['quality_score'] ?? 0) < 70) {
                $this->handleLowScoreSolution($analysis);
            } else {
                // Check if all task submissions for this challenge are ready
                $this->checkChallengeCompletion();
            }

            // Award reputation points based on quality
            $this->awardReputationPoints(
                $analysis['quality_score'] ?? 0,
                $analysis['solves_task'] ?? false
            );

        }, [
            'submission_id' => $this->submission->id,
            'task_id' => $this->submission->task_id,
            'volunteer_id' => $this->submission->volunteer_id,
        ]);
    }

    /**
     * Handle low-score solution - require volunteer to study and resubmit.
     */
    protected function handleLowScoreSolution(array $analysis): void
    {
        Log::info('Low-score solution detected, requesting resubmission', [
            'submission_id' => $this->submission->id,
            'quality_score' => $analysis['quality_score'] ?? 0,
            'solves_task' => $analysis['solves_task'] ?? false,
        ]);

        // Update submission status to revision_requested
        $this->submission->update([
            'status' => 'revision_requested',
            'ai_feedback' => $analysis['feedback'] ?? 'Solution needs improvement. Please review the task requirements and submit an improved solution.',
        ]);

        // Update task assignment - set back to in_progress so volunteer can resubmit
        $assignment = TaskAssignment::where('task_id', $this->submission->task_id)
            ->where('volunteer_id', $this->submission->volunteer_id)
            ->first();

        if ($assignment) {
            $assignment->update([
                'invitation_status' => 'in_progress',
                'submitted_at' => null,
            ]);

            // Notify volunteer about revision requirement
            try {
                $this->submission->volunteer->user->notify(
                    new SolutionRevisionRequiredNotification($this->submission, $analysis)
                );

                Log::info('Revision notification sent to volunteer', [
                    'volunteer_id' => $this->submission->volunteer_id,
                    'submission_id' => $this->submission->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send revision notification', [
                    'submission_id' => $this->submission->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Check if all tasks for the challenge are completed and aggregate solutions.
     */
    protected function checkChallengeCompletion(): void
    {
        $task = $this->submission->task;
        $challenge = $task->challenge;

        // Get all tasks for this challenge
        $allTasks = $challenge->tasks;

        // Check if all tasks have submissions with AI analysis completed
        $allTasksComplete = true;
        foreach ($allTasks as $challengeTask) {
            // Check if task has at least one submission with completed analysis
            $hasCompletedSubmission = WorkSubmission::where('task_id', $challengeTask->id)
                ->where('ai_analysis_status', 'completed')
                ->where('solves_task', true)
                ->exists();

            if (!$hasCompletedSubmission) {
                $allTasksComplete = false;
                break;
            }
        }

        if ($allTasksComplete) {
            Log::info('All tasks completed for challenge', [
                'challenge_id' => $challenge->id,
            ]);

            // Dispatch job to aggregate solutions and notify company
            AggregateChallengeCompletion::dispatch($challenge)
                ->delay(now()->addSeconds(5));
        }
    }

    /**
     * Award reputation points based on solution quality.
     */
    protected function awardReputationPoints(int $qualityScore, bool $solvesTask): void
    {
        $volunteer = $this->submission->volunteer;

        if (!$volunteer) {
            return;
        }

        // Determine points based on quality score and whether it solves the task
        $points = 0;
        $reason = '';

        if ($solvesTask) {
            $points = match (true) {
                $qualityScore >= 90 => 20,  // Excellent solution: 20 points
                $qualityScore >= 80 => 15,  // Good solution: 15 points
                $qualityScore >= 70 => 10,  // Acceptable solution: 10 points
                default => 5,                // Below threshold but still counts: 5 points
            };
            $reason = "Task solution accepted (AI quality score: {$qualityScore}/100)";
        } else {
            // Solution doesn't fully solve the task, but award small points for effort
            $points = match (true) {
                $qualityScore >= 60 => 3,   // Partial solution: 3 points
                default => 1,                // Attempted solution: 1 point
            };
            $reason = "Task solution submitted (needs revision, quality score: {$qualityScore}/100)";
        }

        if ($points > 0) {
            // Update volunteer's reputation score
            $oldScore = $volunteer->reputation_score ?? 50;
            $newScore = $oldScore + $points;

            $volunteer->update(['reputation_score' => $newScore]);

            // Record in reputation history
            ReputationHistory::create([
                'volunteer_id' => $volunteer->id,
                'change_amount' => $points,
                'new_total' => $newScore,
                'reason' => $reason,
                'related_type' => WorkSubmission::class,
                'related_id' => $this->submission->id,
                'created_at' => now(),
            ]);

            Log::info('Reputation points awarded for solution', [
                'volunteer_id' => $volunteer->id,
                'submission_id' => $this->submission->id,
                'points' => $points,
                'new_score' => $newScore,
            ]);
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['submission_id' => $this->submission->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'submission_id' => $this->submission->id,
            'task_id' => $this->submission->task_id,
        ]);

        $this->submission->update(['ai_analysis_status' => 'failed']);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'submission:' . $this->submission->id,
            'task:' . $this->submission->task_id,
            'solution_analysis',
        ];
    }
}
