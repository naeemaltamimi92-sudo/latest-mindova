<?php

namespace App\Console\Commands;

use App\Jobs\AnalyzeChallengeBrief;
use App\Jobs\AnalyzeVolunteerCV;
use App\Models\Challenge;
use App\Models\Volunteer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetryFailedAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'analysis:retry
                            {--type=all : Type of analysis to retry (all, challenges, volunteers)}
                            {--id= : Specific ID to retry}
                            {--force : Force retry even if recently attempted}
                            {--clear-circuit-breaker : Reset circuit breaker for all jobs}
                            {--status : Show job health status only}';

    /**
     * The console command description.
     */
    protected $description = 'Retry failed AI analysis jobs and manage job health';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('status')) {
            return $this->showStatus();
        }

        if ($this->option('clear-circuit-breaker')) {
            return $this->clearCircuitBreakers();
        }

        $type = $this->option('type');
        $specificId = $this->option('id');
        $force = $this->option('force');

        $this->info('Starting analysis retry process...');
        $this->newLine();

        $retriedCount = 0;

        if ($type === 'all' || $type === 'challenges') {
            $retriedCount += $this->retryChallenges($specificId, $force);
        }

        if ($type === 'all' || $type === 'volunteers') {
            $retriedCount += $this->retryVolunteers($specificId, $force);
        }

        $this->newLine();
        $this->info("Total jobs dispatched: {$retriedCount}");

        // Clear failed jobs from queue
        $this->clearFailedJobs();

        return Command::SUCCESS;
    }

    /**
     * Retry failed challenge analyses.
     */
    protected function retryChallenges(?string $specificId, bool $force): int
    {
        $this->info('Checking challenges with failed analysis...');

        $query = Challenge::query();

        if ($specificId) {
            $query->where('id', $specificId);
        } else {
            $query->where(function ($q) {
                $q->where('ai_analysis_status', 'failed')
                    ->orWhere('ai_analysis_status', 'pending')
                    ->orWhere(function ($subQ) {
                        $subQ->where('ai_analysis_status', 'processing')
                            ->where('updated_at', '<', now()->subMinutes(30));
                    });
            })
                ->whereNotIn('status', ['rejected', 'completed', 'delivered']);
        }

        $challenges = $query->get();

        if ($challenges->isEmpty()) {
            $this->info('No challenges need retry.');
            return 0;
        }

        $count = 0;
        $this->withProgressBar($challenges, function ($challenge) use (&$count, $force) {
            // Check if recently dispatched
            $cacheKey = "retry_challenge_{$challenge->id}";
            if (!$force && Cache::has($cacheKey)) {
                return;
            }

            // Reset the challenge status
            $challenge->update([
                'status' => 'submitted',
                'ai_analysis_status' => 'pending',
            ]);

            // Dispatch the job
            AnalyzeChallengeBrief::dispatch($challenge)
                ->delay(now()->addSeconds($count * 5)); // Stagger dispatches

            // Mark as recently dispatched
            Cache::put($cacheKey, true, now()->addMinutes(10));

            Log::info('Retrying challenge analysis', ['challenge_id' => $challenge->id]);

            $count++;
        });

        $this->newLine();
        $this->info("Dispatched {$count} challenge analysis jobs.");

        return $count;
    }

    /**
     * Retry failed volunteer CV analyses.
     */
    protected function retryVolunteers(?string $specificId, bool $force): int
    {
        $this->info('Checking volunteers with failed CV analysis...');

        $query = Volunteer::query();

        if ($specificId) {
            $query->where('id', $specificId);
        } else {
            $query->where(function ($q) {
                $q->where('ai_analysis_status', 'failed')
                    ->orWhere('ai_analysis_status', 'pending')
                    ->orWhere(function ($subQ) {
                        $subQ->where('ai_analysis_status', 'processing')
                            ->where('updated_at', '<', now()->subMinutes(30));
                    });
            })
                ->whereNotNull('cv_file_path');
        }

        $volunteers = $query->get();

        if ($volunteers->isEmpty()) {
            $this->info('No volunteers need CV analysis retry.');
            return 0;
        }

        $count = 0;
        $this->withProgressBar($volunteers, function ($volunteer) use (&$count, $force) {
            // Check if recently dispatched
            $cacheKey = "retry_volunteer_{$volunteer->id}";
            if (!$force && Cache::has($cacheKey)) {
                return;
            }

            // Reset the volunteer status
            $volunteer->update([
                'ai_analysis_status' => 'pending',
            ]);

            // Dispatch the job
            AnalyzeVolunteerCV::dispatch($volunteer)
                ->delay(now()->addSeconds($count * 5)); // Stagger dispatches

            // Mark as recently dispatched
            Cache::put($cacheKey, true, now()->addMinutes(10));

            Log::info('Retrying volunteer CV analysis', ['volunteer_id' => $volunteer->id]);

            $count++;
        });

        $this->newLine();
        $this->info("Dispatched {$count} volunteer CV analysis jobs.");

        return $count;
    }

    /**
     * Show job health status.
     */
    protected function showStatus(): int
    {
        $this->info('Job Health Status');
        $this->newLine();

        // Check circuit breakers
        $jobTypes = [
            'AnalyzeChallengeBrief',
            'EvaluateChallengeComplexity',
            'DecomposeChallengeTasks',
            'MatchVolunteersToTasks',
            'AnalyzeVolunteerCV',
            'AnalyzeCommentQuality',
            'AnalyzeSolutionQuality',
            'ScoreIdea',
            'FormTeamsForChallenge',
        ];

        $circuitBreakerStatus = [];
        foreach ($jobTypes as $jobType) {
            $key = "circuit_breaker:{$jobType}";
            $failures = Cache::get($key, 0);
            $status = $failures >= 10 ? 'OPEN (blocked)' : ($failures > 0 ? "Warning ({$failures} failures)" : 'OK');
            $circuitBreakerStatus[] = [
                'Job Type' => $jobType,
                'Failures' => $failures,
                'Status' => $status,
            ];
        }

        $this->table(['Job Type', 'Failures', 'Status'], $circuitBreakerStatus);
        $this->newLine();

        // Check rate limiting
        $rateLimitKey = 'rate_limit:openai_api';
        $rateLimit = Cache::get($rateLimitKey, 0);
        $this->info("API Rate Limit: {$rateLimit}/50 requests in last minute");
        $this->newLine();

        // Show pending challenges
        $pendingChallenges = Challenge::where('ai_analysis_status', 'pending')
            ->whereNotIn('status', ['rejected', 'completed', 'delivered'])
            ->count();
        $processingChallenges = Challenge::where('ai_analysis_status', 'processing')
            ->count();
        $failedChallenges = Challenge::where('ai_analysis_status', 'failed')
            ->count();

        $this->table(['Status', 'Count'], [
            ['Pending Challenges', $pendingChallenges],
            ['Processing Challenges', $processingChallenges],
            ['Failed Challenges', $failedChallenges],
        ]);

        // Show pending volunteers
        $pendingVolunteers = Volunteer::where('ai_analysis_status', 'pending')
            ->whereNotNull('cv_file_path')
            ->count();
        $failedVolunteers = Volunteer::where('ai_analysis_status', 'failed')
            ->count();

        $this->table(['Status', 'Count'], [
            ['Pending Volunteer CVs', $pendingVolunteers],
            ['Failed Volunteer CVs', $failedVolunteers],
        ]);

        // Show failed jobs in queue
        $failedJobs = DB::table('failed_jobs')->count();
        $this->info("Failed Jobs in Queue: {$failedJobs}");

        return Command::SUCCESS;
    }

    /**
     * Clear all circuit breakers.
     */
    protected function clearCircuitBreakers(): int
    {
        $jobTypes = [
            'AnalyzeChallengeBrief',
            'EvaluateChallengeComplexity',
            'DecomposeChallengeTasks',
            'MatchVolunteersToTasks',
            'AnalyzeVolunteerCV',
            'AnalyzeCommentQuality',
            'AnalyzeSolutionQuality',
            'ScoreIdea',
            'FormTeamsForChallenge',
            'ProcessChallengeAttachment',
            'AggregateChallengeCompletion',
        ];

        foreach ($jobTypes as $jobType) {
            $key = "circuit_breaker:{$jobType}";
            Cache::forget($key);
        }

        // Also clear rate limit
        Cache::forget('rate_limit:openai_api');

        $this->info('All circuit breakers and rate limits have been reset.');

        return Command::SUCCESS;
    }

    /**
     * Clear failed jobs from the queue.
     */
    protected function clearFailedJobs(): void
    {
        $failedCount = DB::table('failed_jobs')->count();

        if ($failedCount > 0) {
            if ($this->confirm("There are {$failedCount} failed jobs. Do you want to clear them?", false)) {
                DB::table('failed_jobs')->truncate();
                $this->info('Failed jobs cleared.');
            }
        }
    }
}
