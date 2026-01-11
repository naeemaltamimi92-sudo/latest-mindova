<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Challenge;
use App\Models\Task;
use App\Services\AI\VolunteerMatchingService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class MatchVolunteersToTasks implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 900; // 15 minutes for multiple tasks

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 1200; // 20 minutes

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
        return 'match_volunteers_' . $this->challenge->id;
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
        VolunteerMatchingService $matchingService,
        NotificationService $notificationService
    ): void {
        $this->executeWithRobustHandling(function () use ($matchingService, $notificationService) {
            // Refresh the model
            $this->challenge->refresh();

            // Get all pending tasks for this challenge
            $tasks = Task::where('challenge_id', $this->challenge->id)
                ->where('status', 'pending')
                ->get();

            if ($tasks->isEmpty()) {
                Log::warning('No pending tasks found for matching', [
                    'challenge_id' => $this->challenge->id,
                ]);

                // Check if there are any tasks at all
                $allTasks = Task::where('challenge_id', $this->challenge->id)->count();
                if ($allTasks === 0) {
                    // No tasks - but don't dispatch decomposition here to prevent loops
                    // The decomposition should be triggered from complexity evaluation
                    Log::info('No tasks found, matching job exiting', [
                        'challenge_id' => $this->challenge->id,
                    ]);
                }
                return;
            }

            $totalMatches = 0;
            $totalAssignments = 0;
            $failedTasks = [];

            foreach ($tasks as $task) {
                try {
                    Log::info('Matching volunteers to task', [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                    ]);

                    // Find matches for this task
                    $matches = $matchingService->matchVolunteersToTask($task, maxMatches: 5);

                    if (empty($matches)) {
                        Log::warning('No suitable volunteers found for task', [
                            'task_id' => $task->id,
                        ]);
                        continue;
                    }

                    Log::info('Found volunteer matches', [
                        'task_id' => $task->id,
                        'match_count' => count($matches),
                    ]);

                    // Create task assignments
                    $assignments = $matchingService->createAssignments($task, $matches);

                    $totalMatches += count($matches);
                    $totalAssignments += count($assignments);

                    // Send notifications to matched volunteers
                    foreach ($assignments as $assignment) {
                        try {
                            $notificationService->notifyTaskAssignment($assignment);
                        } catch (\Exception $e) {
                            Log::error('Failed to send task assignment notification', [
                                'assignment_id' => $assignment->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    Log::info('Created task assignments', [
                        'task_id' => $task->id,
                        'assignment_count' => count($assignments),
                    ]);

                    // Update task status
                    if (!empty($assignments)) {
                        $task->update(['status' => 'matching']);
                    }

                    // Small delay between tasks to prevent API overload
                    usleep(500000); // 0.5 second

                } catch (\Exception $e) {
                    Log::error('Failed to match volunteers to task', [
                        'task_id' => $task->id,
                        'error' => $e->getMessage(),
                    ]);
                    $failedTasks[] = $task->id;
                    // Continue with other tasks even if one fails
                    continue;
                }
            }

            Log::info('Volunteer matching completed for challenge', [
                'challenge_id' => $this->challenge->id,
                'total_matches' => $totalMatches,
                'total_assignments' => $totalAssignments,
                'failed_tasks' => $failedTasks,
            ]);

            // Dispatch team formation job only if teams don't already exist
            $hasTeams = $this->challenge->teams()->exists();
            if (!$hasTeams && $totalAssignments > 0) {
                FormTeamsForChallenge::dispatch($this->challenge)
                    ->delay(now()->addSeconds(15));

                Log::info('Dispatched team formation job for challenge', [
                    'challenge_id' => $this->challenge->id,
                ]);
            } else {
                Log::info('Skipping team formation dispatch', [
                    'challenge_id' => $this->challenge->id,
                    'has_teams' => $hasTeams,
                    'total_assignments' => $totalAssignments,
                ]);
            }

            // Notify owner that analysis is complete
            try {
                $notificationService->notifyChallengeAnalyzed($this->challenge);
            } catch (\Exception $e) {
                Log::error('Failed to send challenge analyzed notification', [
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

        // Challenge is still active, just matching failed
        // Admin can manually retry or assign volunteers
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'challenge:' . $this->challenge->id,
            'volunteer_matching',
        ];
    }
}
