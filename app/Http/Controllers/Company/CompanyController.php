<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Services\Company\CompanyRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyRegistrationService $registrationService
    ) {}

    /**
     * Complete company profile after OAuth.
     */
    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $user = $request->user();

        if ($user->company) {
            return response()->json([
                'message' => 'Profile already completed',
                'company' => new CompanyResource($user->company),
            ], 422);
        }

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $company = $this->registrationService->completeProfile($user, $validated);

        return response()->json([
            'message' => 'Profile completed successfully',
            'company' => new CompanyResource($company),
        ], 201);
    }

    /**
     * Show company profile.
     */
    public function showProfile(Request $request)
    {
        $user = $request->user();

        if (!$user->company) {
            return response()->json([
                'message' => 'Company profile not found',
            ], 404);
        }

        return response()->json([
            'company' => new CompanyResource($user->company->load('challenges')),
        ]);
    }

    /**
     * Update company profile.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'industry' => 'sometimes|string|max:255',
            'website' => 'sometimes|url|max:255',
            'description' => 'sometimes|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if (!$user->company) {
            return response()->json([
                'message' => 'Company profile not found',
            ], 404);
        }

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($user->company->logo_path) {
                Storage::disk('public')->delete($user->company->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $company = $this->registrationService->updateProfile($user->company, $validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'company' => new CompanyResource($company),
        ]);
    }
}
