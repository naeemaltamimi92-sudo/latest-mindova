<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Http\Resources\VolunteerResource;
use App\Jobs\AnalyzeVolunteerCV;
use App\Services\Volunteer\VolunteerRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VolunteerController extends Controller
{
    public function __construct(
        protected VolunteerRegistrationService $registrationService
    ) {}

    /**
     * Complete volunteer profile after OAuth.
     */
    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'availability_hours_per_week' => 'required|integer|min:0|max:168',
            'bio' => 'nullable|string|max:1000',
            'field' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if ($user->volunteer) {
            return response()->json([
                'message' => 'Profile already completed',
                'volunteer' => new VolunteerResource($user->volunteer),
            ], 422);
        }

        $volunteer = $this->registrationService->completeProfile($user, $validated);

        return response()->json([
            'message' => 'Profile completed successfully',
            'volunteer' => new VolunteerResource($volunteer),
        ], 201);
    }

    /**
     * Show volunteer profile.
     */
    public function showProfile(Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Volunteer profile not found',
            ], 404);
        }

        return response()->json([
            'volunteer' => new VolunteerResource($user->volunteer->load('skills')),
        ]);
    }

    /**
     * Update volunteer profile.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'availability_hours_per_week' => 'sometimes|integer|min:0|max:168',
            'bio' => 'sometimes|string|max:1000',
            'field' => 'sometimes|string|max:255',
        ]);

        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Volunteer profile not found',
            ], 404);
        }

        $volunteer = $this->registrationService->updateProfile($user->volunteer, $validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'volunteer' => new VolunteerResource($volunteer),
        ]);
    }

    /**
     * Upload CV for AI analysis.
     */
    public function uploadCV(Request $request)
    {
        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Volunteer profile not found',
            ], 404);
        }

        // Delete old CV if exists
        if ($user->volunteer->cv_file_path) {
            Storage::delete($user->volunteer->cv_file_path);
        }

        // Store new CV
        $path = $request->file('cv')->store('cvs', 'local');

        // Update volunteer record
        $user->volunteer->update([
            'cv_file_path' => $path,
            'ai_analysis_status' => 'pending',
        ]);

        // Dispatch CV analysis job
        AnalyzeVolunteerCV::dispatch($user->volunteer);

        return response()->json([
            'message' => 'CV uploaded successfully. Analysis will begin shortly.',
            'volunteer' => new VolunteerResource($user->volunteer->fresh()),
        ]);
    }
}
