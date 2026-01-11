<?php

namespace App\Services\Volunteer;

use App\Models\User;
use App\Models\Volunteer;

class VolunteerRegistrationService
{
    /**
     * Complete volunteer profile after OAuth.
     */
    public function completeProfile(User $user, array $data): Volunteer
    {
        // Update user type to volunteer if not already
        if (!$user->isVolunteer()) {
            $user->update(['user_type' => 'volunteer']);
        }

        // Create volunteer profile
        $volunteer = Volunteer::create([
            'user_id' => $user->id,
            'field' => $data['field'] ?? null,
            'availability_hours_per_week' => $data['availability_hours_per_week'] ?? 0,
            'bio' => $data['bio'] ?? null,
            'reputation_score' => 50.00, // Default starting reputation
            'ai_analysis_status' => 'pending',
            'validation_status' => 'pending',
            'skills_normalized' => false,
        ]);

        return $volunteer;
    }

    /**
     * Update volunteer availability and bio.
     */
    public function updateProfile(Volunteer $volunteer, array $data): Volunteer
    {
        $volunteer->update([
            'field' => $data['field'] ?? $volunteer->field,
            'availability_hours_per_week' => $data['availability_hours_per_week'] ?? $volunteer->availability_hours_per_week,
            'bio' => $data['bio'] ?? $volunteer->bio,
        ]);

        return $volunteer->fresh();
    }
}
