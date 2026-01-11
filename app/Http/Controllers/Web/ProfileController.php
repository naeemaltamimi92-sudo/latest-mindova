<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VolunteerService;
use App\Services\CompanyService;

class ProfileController extends Controller
{
    protected $volunteerService;
    protected $companyService;

    public function __construct(VolunteerService $volunteerService, CompanyService $companyService)
    {
        $this->volunteerService = $volunteerService;
        $this->companyService = $companyService;
    }

    /**
     * Complete volunteer profile.
     */
    public function completeVolunteerProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'field' => 'required|string|max:255',
                'availability_hours_per_week' => 'required|integer|min:0|max:168',
                'bio' => 'nullable|string|max:1000',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);

            $this->volunteerService->updateProfile($request->user(), $validated);

            // Redirect to NDA signing page - mandatory for all volunteers
            return redirect()->route('nda.general')
                ->with('success', 'Profile created successfully! Please sign the NDA to continue.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Complete company profile.
     */
    public function completeCompanyProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'industry' => 'nullable|string|max:100',
                'website' => 'nullable|url|max:255',
                'description' => 'nullable|string|max:1000',
                'logo' => 'nullable|image|max:5120',
            ]);

            $this->companyService->updateProfile($request->user(), $validated);

            return redirect()->route('dashboard')->with('success', 'Profile completed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Update volunteer profile.
     */
    public function updateVolunteerProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'field' => 'sometimes|string|max:255',
                'availability_hours_per_week' => 'required|integer|min:0|max:168',
                'bio' => 'nullable|string|max:1000',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);

            $this->volunteerService->updateProfile($request->user(), $validated);

            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Update company profile.
     */
    public function updateCompanyProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'industry' => 'nullable|string|max:100',
                'website' => 'nullable|url|max:255',
                'description' => 'nullable|string|max:1000',
                'logo' => 'nullable|image|max:5120',
            ]);

            $this->companyService->updateProfile($request->user(), $validated);

            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
