<?php

namespace App\Services;

use App\Models\User;
use App\Models\Volunteer;
use App\Jobs\AnalyzeVolunteerCV;
use Illuminate\Support\Facades\Storage;

class VolunteerService
{
    public function __construct(
        private readonly ReputationService $reputation,
    ) {}

    public function updateProfile(User $user, array $data): Volunteer
    {
        $isStudent = $data['is_student'] ?? false;
        $isNewProfile = !$user->volunteer;

        if ($isNewProfile) {
            if (!$user->isVolunteer()) {
                $user->update(['user_type' => 'volunteer']);
            }

            $profilePicture = session('volunteer_profile_picture');

            $volunteerData = [
                'user_id' => $user->id,
                'field' => $data['field'] ?? null,
                'availability_hours_per_week' => $data['availability_hours_per_week'] ?? 0,
                'bio' => $data['bio'] ?? null,
                'profile_picture' => $profilePicture,
                'reputation_score' => 0,
                'trust_score' => 100.0,
                'skills_normalized' => false,
            ];

            if ($isStudent) {
                $volunteerData['experience_level'] = 'Student';
                $volunteerData['ai_analysis_status'] = 'completed';
                $volunteerData['validation_status'] = 'passed';
                $volunteerData['years_of_experience'] = 0;
            } else {
                $volunteerData['ai_analysis_status'] = 'pending';
                $volunteerData['validation_status'] = 'pending';
            }

            $volunteer = Volunteer::create($volunteerData);
            session()->forget('volunteer_profile_picture');

            // Grant profile completion stars (one-time)
            $this->reputation->award($volunteer, 'profile_completed');
        } else {
            $volunteer = $user->volunteer;

            $updateData = [
                'field' => $data['field'] ?? $volunteer->field,
                'availability_hours_per_week' => $data['availability_hours_per_week'] ?? $volunteer->availability_hours_per_week,
                'bio' => $data['bio'] ?? $volunteer->bio,
            ];

            if ($isStudent && $volunteer->experience_level !== 'Student') {
                $updateData['experience_level'] = 'Student';
                $updateData['ai_analysis_status'] = 'completed';
                $updateData['validation_status'] = 'passed';
                $updateData['years_of_experience'] = 0;
            }

            $volunteer->update($updateData);
        }

        // CV upload: award stars once per volunteer (first upload only)
        if (!$isStudent && isset($data['cv']) && $data['cv']) {
            if ($volunteer->cv_file_path) {
                Storage::delete($volunteer->cv_file_path);
            }

            $path = $data['cv']->store('cvs', 'local');
            $volunteer->update([
                'cv_file_path' => $path,
                'ai_analysis_status' => 'pending',
            ]);

            AnalyzeVolunteerCV::dispatch($volunteer);

            // Stars only for the first CV upload
            if (!$this->reputation->hasAlreadyEarned($volunteer, 'cv_uploaded')) {
                $this->reputation->award($volunteer, 'cv_uploaded');
            }
        }

        if (isset($data['profile_picture']) && $data['profile_picture']) {
            if ($volunteer->profile_picture) {
                Storage::disk('public')->delete($volunteer->profile_picture);
            }

            $file = $data['profile_picture'];
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            $volunteer->update(['profile_picture' => $path]);
        }

        return $volunteer->fresh();
    }
}
