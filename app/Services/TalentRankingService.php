<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\ExpertChallengeAssignment;
use App\Models\TaskAssignment;
use App\Models\Volunteer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TalentRankingService
{
    // Weights must sum to 100
    private const WEIGHTS = [
        'stars'              => 25,
        'trust'              => 20,
        'verified_projects'  => 15,
        'success_rate'       => 15,
        'expert_approvals'   => 10,
        'recency'            => 10,
        'leadership'         => 5,
    ];

    private const CACHE_TTL_SECONDS = 3600; // 1 hour
    private const CACHE_KEY_PREFIX  = 'talent_score_v2:';

    /**
     * Compute (or return cached) talent score for a single volunteer.
     * Invalidate with invalidate() on task-completion events.
     */
    public function score(Volunteer $volunteer): float
    {
        return (float) Cache::remember(
            self::CACHE_KEY_PREFIX . $volunteer->id,
            self::CACHE_TTL_SECONDS,
            fn () => $this->computeScore($volunteer)
        );
    }

    /**
     * Drop the cached score so the next call recomputes.
     * Call this from ReputationService::award() and on task completion.
     */
    public function invalidate(Volunteer $volunteer): void
    {
        Cache::forget(self::CACHE_KEY_PREFIX . $volunteer->id);
    }

    /**
     * Rank a collection of volunteers by marketplace score, highest first.
     *
     * Uses bulkScore() to load all necessary data in 4 queries instead of
     * 6 × N queries, then returns the collection sorted descending.
     */
    public function rank(Collection $volunteers): Collection
    {
        return $this->bulkScore($volunteers)
            ->sortByDesc('score')
            ->values()
            ->pluck('volunteer');
    }

    /**
     * Score and rank a collection in bulk — 4 queries total regardless of N.
     *
     * Returns a collection of ['volunteer' => Volunteer, 'score' => float],
     * sorted descending by score.
     */
    public function bulkScore(Collection $volunteers): Collection
    {
        if ($volunteers->isEmpty()) {
            return collect();
        }

        $volunteerIds = $volunteers->pluck('id');
        $userIds      = $volunteers->pluck('user_id');

        // ── Query 1: verified project certificates ────────────────────────────
        $verifiedCerts = Certificate::whereIn('user_id', $userIds)
            ->where('company_confirmed', true)
            ->where('is_revoked', false)
            ->selectRaw('user_id, COUNT(*) as cnt')
            ->groupBy('user_id')
            ->pluck('cnt', 'user_id');

        // ── Query 2: expert-approved certificates ─────────────────────────────
        $expertApprovals = Certificate::whereIn('user_id', $userIds)
            ->whereNotNull('expert_approved_at')
            ->selectRaw('user_id, COUNT(*) as cnt')
            ->groupBy('user_id')
            ->pluck('cnt', 'user_id');

        // ── Query 3: assignment stats (accepted, completed, last activity) ─────
        $assignmentStats = TaskAssignment::whereIn('volunteer_id', $volunteerIds)
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'completed'])
            ->selectRaw('
                volunteer_id,
                COUNT(*) as total_accepted,
                SUM(CASE WHEN invitation_status = "completed" THEN 1 ELSE 0 END) as total_completed,
                MAX(updated_at) as last_activity
            ')
            ->groupBy('volunteer_id')
            ->get()
            ->keyBy('volunteer_id');

        // ── Query 4: leadership (lead_expert completions) ─────────────────────
        $leaders = ExpertChallengeAssignment::whereIn('volunteer_id', $volunteerIds)
            ->where('role', 'lead_expert')
            ->where('status', 'completed')
            ->pluck('volunteer_id')
            ->flip(); // flip to use isset() for O(1) lookup

        return $volunteers->map(function (Volunteer $volunteer) use (
            $verifiedCerts, $expertApprovals, $assignmentStats, $leaders
        ) {
            $score = $this->computeFromBulk(
                $volunteer,
                $verifiedCerts,
                $expertApprovals,
                $assignmentStats,
                $leaders
            );
            return ['volunteer' => $volunteer, 'score' => $score];
        });
    }

    /**
     * Full score breakdown for a volunteer's profile page.
     */
    public function scoreBreakdown(Volunteer $volunteer): array
    {
        $stars   = $volunteer->stars ?? $volunteer->reputation_score ?? 0;
        $trust   = $volunteer->trust_score ?? 100.0;

        $verifiedProjects = Certificate::where('user_id', $volunteer->user_id)
            ->where('company_confirmed', true)->where('is_revoked', false)->count();

        $totalAccepted  = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'completed'])->count();
        $totalCompleted = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'completed')->count();
        $successRate = $totalAccepted > 0 ? round(($totalCompleted / $totalAccepted) * 100) : 0;

        $expertApprovals = Certificate::where('user_id', $volunteer->user_id)
            ->whereNotNull('expert_approved_at')->count();

        return [
            'total'             => $this->score($volunteer),
            'stars'             => $stars,
            'trust_score'       => $trust,
            'verified_projects' => $verifiedProjects,
            'success_rate'      => $successRate,
            'expert_approvals'  => $expertApprovals,
        ];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function computeScore(Volunteer $volunteer): float
    {
        $stars   = $volunteer->stars ?? $volunteer->reputation_score ?? 0;
        $trust   = (float) ($volunteer->trust_score ?? 100.0);

        $verifiedProjects = Certificate::where('user_id', $volunteer->user_id)
            ->where('company_confirmed', true)->where('is_revoked', false)->count();

        $totalAccepted  = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'completed'])->count();
        $totalCompleted = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'completed')->count();
        $successRate = $totalAccepted > 0 ? ($totalCompleted / $totalAccepted) * 100 : 0;

        $expertApprovals = Certificate::where('user_id', $volunteer->user_id)
            ->whereNotNull('expert_approved_at')->count();

        $lastActivity = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->latest('updated_at')->value('updated_at');
        $daysSince    = $lastActivity ? now()->diffInDays($lastActivity) : 365;
        $recencyScore = max(0, 100 - ($daysSince * 1.5));

        $leadershipScore = ExpertChallengeAssignment::where('volunteer_id', $volunteer->id)
            ->where('role', 'lead_expert')->where('status', 'completed')->exists() ? 100 : 0;

        return $this->calculate($stars, $trust, $verifiedProjects, $successRate, $expertApprovals, $recencyScore, $leadershipScore);
    }

    private function computeFromBulk(
        Volunteer $volunteer,
        Collection $verifiedCerts,
        Collection $expertApprovals,
        Collection $assignmentStats,
        Collection $leaders
    ): float {
        $stars   = $volunteer->stars ?? $volunteer->reputation_score ?? 0;
        $trust   = (float) ($volunteer->trust_score ?? 100.0);

        $verified = (int) ($verifiedCerts->get($volunteer->user_id, 0));
        $approvals = (int) ($expertApprovals->get($volunteer->user_id, 0));

        $stats          = $assignmentStats->get($volunteer->id);
        $totalAccepted  = (int) ($stats->total_accepted ?? 0);
        $totalCompleted = (int) ($stats->total_completed ?? 0);
        $lastActivity   = $stats->last_activity ?? null;

        $successRate  = $totalAccepted > 0 ? ($totalCompleted / $totalAccepted) * 100 : 0;
        $daysSince    = $lastActivity ? now()->diffInDays($lastActivity) : 365;
        $recencyScore = max(0, 100 - ($daysSince * 1.5));
        $leadership   = isset($leaders[$volunteer->id]) ? 100 : 0;

        return $this->calculate($stars, $trust, $verified, $successRate, $approvals, $recencyScore, $leadership);
    }

    private function calculate(
        int $stars,
        float $trust,
        int $verifiedProjects,
        float $successRate,
        int $expertApprovals,
        float $recencyScore,
        float $leadershipScore
    ): float {
        return round(
            (min($stars / 1200, 1) * 100     * self::WEIGHTS['stars']             / 100) +
            ($trust / 100 * 100              * self::WEIGHTS['trust']             / 100) +
            (min($verifiedProjects / 20, 1) * 100 * self::WEIGHTS['verified_projects'] / 100) +
            ($successRate                    * self::WEIGHTS['success_rate']      / 100) +
            (min($expertApprovals / 5, 1) * 100 * self::WEIGHTS['expert_approvals']  / 100) +
            ($recencyScore                   * self::WEIGHTS['recency']           / 100) +
            ($leadershipScore               * self::WEIGHTS['leadership']         / 100),
        1);
    }
}
