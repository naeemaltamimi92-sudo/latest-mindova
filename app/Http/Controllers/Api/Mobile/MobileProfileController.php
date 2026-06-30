<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Volunteer;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MobileProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['volunteer.skills', 'company']);

        return response()->json(['user' => $this->formatFullProfile($user)]);
    }

    public function updateVolunteer(Request $request): JsonResponse
    {
        $user      = $request->user();
        $volunteer = $user->volunteer;

        if (! $volunteer) {
            return response()->json(['message' => 'Volunteer profile not found.'], 404);
        }

        $validated = $request->validate([
            'bio'                          => 'nullable|string|max:1000',
            'field'                        => 'nullable|string|max:255',
            'experience_level'             => 'nullable|in:junior,mid,senior,expert',
            'years_of_experience'          => 'nullable|numeric|min:0|max:50',
            'availability_hours_per_week'  => 'nullable|integer|min:0|max:168',
            'expert_available'             => 'nullable|boolean',
        ]);

        $volunteer->update($validated);

        return response()->json([
            'message'   => 'Profile updated successfully.',
            'volunteer' => $volunteer->fresh(),
        ]);
    }

    public function updateCompany(Request $request): JsonResponse
    {
        $user    = $request->user();
        $company = $user->company;

        if (! $company) {
            return response()->json(['message' => 'Company profile not found.'], 404);
        }

        $validated = $request->validate([
            'name'     => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website'  => 'nullable|url|max:255',
            'bio'      => 'nullable|string|max:1000',
        ]);

        $company->update($validated);

        return response()->json([
            'message' => 'Company profile updated successfully.',
            'company' => $company->fresh(),
        ]);
    }

    public function uploadProfilePicture(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:4096',
        ]);

        $user      = $request->user();
        $volunteer = $user->volunteer;

        if (! $volunteer) {
            return response()->json(['message' => 'Volunteer profile not found.'], 404);
        }

        if ($volunteer->profile_picture) {
            Storage::disk('public')->delete($volunteer->profile_picture);
        }

        $file     = $request->file('image');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('profile_pictures', $filename, 'public');

        $volunteer->update(['profile_picture' => $path]);

        return response()->json([
            'message' => 'Profile picture updated.',
            'url'     => asset('storage/' . $path),
        ]);
    }

    public function certificates(Request $request): JsonResponse
    {
        $user = $request->user();

        $certificates = Certificate::where('user_id', $user->id)
            ->with('challenge')
            ->latest()
            ->get()
            ->map(fn($cert) => [
                'id'             => $cert->id,
                'certificate_id' => $cert->certificate_id,
                'type'           => $cert->type,
                'title'          => $cert->title,
                'issued_at'      => $cert->issued_at,
                'challenge'      => $cert->challenge ? [
                    'id'    => $cert->challenge->id,
                    'title' => $cert->challenge->title,
                ] : null,
                'download_url'   => route('certificates.download', $cert->id),
                'verify_url'     => route('certificates.verify') . '?id=' . $cert->certificate_id,
            ]);

        return response()->json(['certificates' => $certificates]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! \Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->update(['password' => \Hash::make($request->password)]);
        $user->tokens()->where('name', '!=', 'mobile_token')->delete();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    private function formatFullProfile($user): array
    {
        return [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'user_type' => $user->user_type,
            'volunteer' => $user->volunteer ? [
                'id'                      => $user->volunteer->id,
                'profile_picture'         => $user->volunteer->profile_picture
                    ? asset('storage/' . $user->volunteer->profile_picture)
                    : null,
                'bio'                     => $user->volunteer->bio,
                'field'                   => $user->volunteer->field,
                'experience_level'        => $user->volunteer->experience_level,
                'years_of_experience'     => $user->volunteer->years_of_experience,
                'reputation_score'        => $user->volunteer->reputation_score,
                'trust_score'             => $user->volunteer->trust_score,
                'expert_available'        => $user->volunteer->expert_available,
                'availability_hours_per_week' => $user->volunteer->availability_hours_per_week,
                'total_tasks_completed'   => $user->volunteer->total_tasks_completed,
                'ai_analysis_status'      => $user->volunteer->ai_analysis_status,
                'general_nda_signed'      => $user->volunteer->general_nda_signed,
                'skills'                  => $user->volunteer->skills->map(fn($s) => [
                    'id'                => $s->id,
                    'skill_name'        => $s->skill_name,
                    'proficiency_level' => $s->proficiency_level,
                ])->values(),
                'education'              => $user->volunteer->education ?? [],
                'work_experience'        => $user->volunteer->work_experience ?? [],
                'professional_domains'   => $user->volunteer->professional_domains ?? [],
            ] : null,
            'company' => $user->company ? [
                'id'       => $user->company->id,
                'name'     => $user->company->name,
                'logo'     => $user->company->logo ? asset('storage/' . $user->company->logo) : null,
                'industry' => $user->company->industry,
                'website'  => $user->company->website,
                'bio'      => $user->company->bio,
                'total_challenges_submitted' => $user->company->total_challenges_submitted,
            ] : null,
        ];
    }
}
