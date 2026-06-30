<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\ExpertChallengeAssignment;
use App\Models\Volunteer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ExpertSelectionService
{
    // Weights must sum to 100
    const SCORE_WEIGHTS = [
        'stars'           => 30,  // reputation_score normalized against Certified Expert threshold
        'trust'           => 25,  // trust_score (0–100, already normalized)
        'field_match'     => 25,  // exact field match
        'availability'    => 10,  // hours/week capacity
        'completion_rate' => 10,  // historical task completion rate
    ];

    // How many experts to assign per challenge role
    const TEAM_COMPOSITION = [
        'lead_expert'      => 1,
        'domain_expert'    => 2,
        'quality_reviewer' => 1,
    ];

    /**
     * Select and invite the best experts for a challenge.
     * Returns the created ExpertChallengeAssignment records.
     */
    public function assignExpertsToChallenge(Challenge $challenge): Collection
    {
        $pool = $this->buildEligiblePool($challenge);

        if ($pool->isEmpty()) {
            Log::warning('No eligible experts found for challenge', ['challenge_id' => $challenge->id]);
            return collect();
        }

        $scored = $pool->map(fn($v) => $this->scoreExpert($v, $challenge))
                       ->sortByDesc('score');

        $assignments = collect();
        $assigned    = collect();

        foreach (self::TEAM_COMPOSITION as $role => $slots) {
            $filled = 0;
            foreach ($scored as $entry) {
                if ($filled >= $slots) {
                    break;
                }
                if ($assigned->contains($entry['volunteer']->id)) {
                    continue;
                }

                $assignment = ExpertChallengeAssignment::create([
                    'challenge_id'        => $challenge->id,
                    'volunteer_id'        => $entry['volunteer']->id,
                    'role'                => $role,
                    'status'              => 'invited',
                    'selection_score'     => $entry['score'],
                    'selection_reasoning' => $entry['reasoning'],
                    'invited_at'          => now(),
                ]);

                $assignments->push($assignment);
                $assigned->push($entry['volunteer']->id);
                $filled++;
            }
        }

        // Mark challenge as expert_mode with timestamp
        $challenge->update([
            'expert_mode'       => true,
            'expert_assigned_at' => now(),
        ]);

        Log::info('Experts assigned to challenge', [
            'challenge_id' => $challenge->id,
            'count'        => $assignments->count(),
        ]);

        return $assignments;
    }

    /**
     * Score a single expert candidate against the challenge.
     */
    public function scoreExpert(Volunteer $volunteer, Challenge $challenge): array
    {
        $w = self::SCORE_WEIGHTS;

        // Stars: normalize against 1200 (Certified Expert), cap at 100
        $starsScore = min(($volunteer->reputation_score / 1200) * 100, 100);

        // Trust score: already 0–100
        $trustScore = (float) $volunteer->trust_score;

        // Field match
        $fieldScore = ($volunteer->field === $challenge->field) ? 100.0 : 0.0;

        // Availability: normalize against 40 h/week
        $availScore = min(($volunteer->availability_hours_per_week / 40) * 100, 100);

        // Completion rate based on historical assignments
        $totalAssigned   = $volunteer->taskAssignments()->count();
        $totalCompleted  = $volunteer->taskAssignments()->where('invitation_status', 'completed')->count();
        $completionScore = $totalAssigned > 0 ? ($totalCompleted / $totalAssigned) * 100 : 50.0;

        $finalScore = ($starsScore     * $w['stars'] / 100)
                    + ($trustScore     * $w['trust'] / 100)
                    + ($fieldScore     * $w['field_match'] / 100)
                    + ($availScore     * $w['availability'] / 100)
                    + ($completionScore * $w['completion_rate'] / 100);

        $reasoning = sprintf(
            'Stars: %.0f/100, Trust: %.0f/100, Field: %s, Availability: %.0f h/week, Completion: %.0f%%',
            $starsScore,
            $trustScore,
            $volunteer->field === $challenge->field ? 'exact match' : 'no match',
            $volunteer->availability_hours_per_week,
            $completionScore
        );

        return [
            'volunteer' => $volunteer,
            'score'     => round($finalScore, 2),
            'reasoning' => $reasoning,
        ];
    }

    private function buildEligiblePool(Challenge $challenge): Collection
    {
        return Volunteer::with(['user', 'skills', 'taskAssignments'])
            ->where('expert_available', true)          // 500+ stars
            ->where('trust_score', '>=', 60)           // minimum trust threshold
            ->where('ai_analysis_status', 'completed')
            ->where('validation_status', 'passed')
            ->where('general_nda_signed', true)
            ->where('availability_hours_per_week', '>=', 5)
            // Exclude already-assigned experts on this challenge
            ->whereDoesntHave('user', function ($q) use ($challenge) {
                $q->whereHas('volunteer.expertAssignments', function ($q2) use ($challenge) {
                    $q2->where('challenge_id', $challenge->id);
                });
            })
            ->get();
    }
}
