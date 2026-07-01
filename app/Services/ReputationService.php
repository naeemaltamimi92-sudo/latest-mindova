<?php

namespace App\Services;

use App\Models\ReputationHistory;
use App\Models\Volunteer;
use App\Services\TalentRankingService;
use Illuminate\Support\Facades\DB;

class ReputationService
{
    public function __construct(
        protected TalentRankingService $talentRanking
    ) {}
    // Stars awarded per event (base values before complexity multiplier)
    const STAR_EVENTS = [
        'profile_completed'           => 10,
        'cv_uploaded'                 => 20,
        'nda_signed'                  => 5,
        'challenge_participated'      => 5,
        'idea_submitted'              => 5,
        'idea_accepted'               => 30,
        'idea_converted_to_challenge' => 50,
        'task_completed'              => 25,
        'excellent_company_rating'    => 40,
        'project_led'                 => 75,
        'mentored_junior'             => 20,
    ];

    // Maps challenge score (1–10) to a difficulty multiplier
    const COMPLEXITY_MULTIPLIERS = [
        1 => 0.5,  2 => 0.5,
        3 => 0.75, 4 => 0.75,
        5 => 1.0,  6 => 1.0,
        7 => 1.25, 8 => 1.25,
        9 => 1.5,  10 => 2.0,
    ];

    // Trust score deltas (trust is separate from stars and can decrease)
    const TRUST_EVENTS = [
        'deadline_missed'    => -10,
        'policy_violation'   => -20,
        'company_complaint'  => -15,
        'excellent_delivery' => 5,
        'positive_feedback'  => 3,
        'peer_recognition'   => 2,
    ];

    const TIERS = [
        ['slug' => 'explorer',         'name' => 'Explorer',         'min' => 0,    'max' => 49,   'color' => '#6b7280'],
        ['slug' => 'contributor',      'name' => 'Contributor',      'min' => 50,   'max' => 199,  'color' => '#3b82f6'],
        ['slug' => 'trusted_member',   'name' => 'Trusted Member',   'min' => 200,  'max' => 499,  'color' => '#8b5cf6'],
        ['slug' => 'expert_candidate', 'name' => 'Expert Candidate', 'min' => 500,  'max' => 1199, 'color' => '#f59e0b'],
        ['slug' => 'certified_expert', 'name' => 'Certified Expert', 'min' => 1200, 'max' => null, 'color' => '#10b981'],
    ];

    /**
     * Award stars for a named event. Returns the number of stars awarded.
     *
     * $context keys:
     *   complexity_score  – int 1–10, adjusts reward via COMPLEXITY_MULTIPLIERS
     *   related_type      – string, polymorphic relation class name
     *   related_id        – int, polymorphic relation ID
     */
    public function award(Volunteer $volunteer, string $event, array $context = []): int
    {
        $base = self::STAR_EVENTS[$event] ?? 0;
        if ($base === 0) {
            return 0;
        }

        $multiplier = 1.0;
        if (isset($context['complexity_score'])) {
            $score = (int) $context['complexity_score'];
            $multiplier = self::COMPLEXITY_MULTIPLIERS[$score] ?? 1.0;
        }

        $stars = (int) round($base * $multiplier);

        $newTotal = DB::transaction(function () use ($volunteer, $stars, $event, $context) {
            $volunteer->increment('reputation_score', $stars);
            $newTotal = (int) $volunteer->reputation_score;

            ReputationHistory::create([
                'volunteer_id'  => $volunteer->id,
                'change_amount' => $stars,
                'new_total'     => $newTotal,
                'reason'        => $event,
                'related_type'  => $context['related_type'] ?? null,
                'related_id'    => $context['related_id'] ?? null,
                'created_at'    => now(),
            ]);

            // Unlock expert_available flag when crossing Expert Candidate threshold
            if ($newTotal >= 500 && !$volunteer->expert_available) {
                $volunteer->update(['expert_available' => true]);
            }

            return $newTotal;
        });

        $this->talentRanking->invalidate($volunteer);

        return $stars;
    }

    /**
     * Adjust the volunteer's trust score (not the same as stars — can decrease).
     */
    public function adjustTrust(Volunteer $volunteer, string $event): void
    {
        $delta = self::TRUST_EVENTS[$event] ?? 0;
        if ($delta === 0) {
            return;
        }

        DB::statement(
            'UPDATE volunteers SET trust_score = GREATEST(0, LEAST(100, trust_score + ?)) WHERE id = ?',
            [$delta, $volunteer->id]
        );
        $volunteer->refresh();

        $this->talentRanking->invalidate($volunteer);
    }

    /**
     * Check whether this volunteer has already received stars for a specific
     * event on a specific object (prevents double-awarding).
     */
    public function hasAlreadyEarned(Volunteer $volunteer, string $event, ?string $relatedType = null, ?int $relatedId = null): bool
    {
        $query = ReputationHistory::where('volunteer_id', $volunteer->id)
            ->where('reason', $event);

        if ($relatedType && $relatedId) {
            $query->where('related_type', $relatedType)
                  ->where('related_id', $relatedId);
        }

        return $query->exists();
    }

    public function getTier(int $stars): array
    {
        foreach (array_reverse(self::TIERS) as $tier) {
            if ($stars >= $tier['min']) {
                return $tier;
            }
        }
        return self::TIERS[0];
    }

    public function getNextTier(int $stars): ?array
    {
        foreach (self::TIERS as $tier) {
            if ($stars < $tier['min']) {
                return $tier;
            }
        }
        return null;
    }
}
