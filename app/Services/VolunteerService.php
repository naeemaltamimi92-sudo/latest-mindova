<?php

namespace App\Services;

use App\Models\User;
use App\Models\Volunteer;
use App\Jobs\AnalyzeVolunteerCV;
use Illuminate\Support\Facades\Storage;

class VolunteerService
{
    /**
     * Update volunteer profile with optional CV upload.
     */
    public function updateProfile(User $user, array $data): Volunteer
    {
        // Create volunteer profile if it doesn't exist
        if (!$user->volunteer) {
            // Update user type to volunteer if not already
            if (!$user->isVolunteer()) {
                $user->update(['user_type' => 'volunteer']);
            }

            // Get profile picture from session if available
            $profilePicture = session('volunteer_profile_picture');

            $volunteer = Volunteer::create([
                'user_id' => $user->id,
                'field' => $data['field'] ?? null,
                'availability_hours_per_week' => $data['availability_hours_per_week'] ?? 0,
                'bio' => $data['bio'] ?? null,
                'profile_picture' => $profilePicture,
                'reputation_score' => 50.00,
                'ai_analysis_status' => 'pending',
                'validation_status' => 'pending',
                'skills_normalized' => false,
            ]);

            // Clear the profile picture from session after using it
            session()->forget('volunteer_profile_picture');
        } else {
            $volunteer = $user->volunteer;

            // Update basic fields
            $volunteer->update([
                'field' => $data['field'] ?? $volunteer->field,
                'availability_hours_per_week' => $data['availability_hours_per_week'] ?? $volunteer->availability_hours_per_week,
                'bio' => $data['bio'] ?? $volunteer->bio,
            ]);
        }

        // Handle CV upload if provided
        if (isset($data['cv']) && $data['cv']) {
            // Delete old CV if exists
            if ($volunteer->cv_file_path) {
                Storage::delete($volunteer->cv_file_path);
            }

            // Store new CV
            $path = $data['cv']->store('cvs', 'local');

            // Update volunteer record
            $volunteer->update([
                'cv_file_path' => $path,
                'ai_analysis_status' => 'pending',
            ]);

            // Dispatch CV analysis job
            AnalyzeVolunteerCV::dispatch($volunteer);
        }

        // Handle profile picture upload if provided
        if (isset($data['profile_picture']) && $data['profile_picture']) {
            // Delete old profile picture if exists
            if ($volunteer->profile_picture) {
                Storage::disk('public')->delete($volunteer->profile_picture);
            }

            // Store new profile picture
            $file = $data['profile_picture'];
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures', $filename, 'public');

            // Update volunteer record
            $volunteer->update([
                'profile_picture' => $path,
            ]);
        }

        return $volunteer->fresh();
    }
}
