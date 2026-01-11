<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    /**
     * Update company profile with optional logo upload.
     */
    public function updateProfile(User $user, array $data): Company
    {
        // Create company profile if it doesn't exist
        if (!$user->company) {
            // Update user type to company if not already
            if (!$user->isCompany()) {
                $user->update(['user_type' => 'company']);
            }

            // Handle logo upload if provided
            $logoPath = null;
            if (isset($data['logo']) && $data['logo']) {
                $logoPath = $data['logo']->store('logos', 'public');
            }

            $company = Company::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'commercial_register' => $data['commercial_register'] ?? null,
                'industry' => $data['industry'] ?? null,
                'website' => $data['website'] ?? null,
                'description' => $data['description'] ?? null,
                'logo_path' => $logoPath,
                'total_challenges_submitted' => 0,
            ]);
        } else {
            $company = $user->company;

            // Handle logo upload if provided
            if (isset($data['logo']) && $data['logo']) {
                // Delete old logo if exists
                if ($company->logo_path) {
                    Storage::disk('public')->delete($company->logo_path);
                }

                // Store new logo
                $data['logo_path'] = $data['logo']->store('logos', 'public');
                unset($data['logo']); // Remove the UploadedFile object
            }

            // Update company fields
            $company->update([
                'company_name' => $data['company_name'] ?? $company->company_name,
                'commercial_register' => $data['commercial_register'] ?? $company->commercial_register,
                'industry' => $data['industry'] ?? $company->industry,
                'website' => $data['website'] ?? $company->website,
                'description' => $data['description'] ?? $company->description,
                'logo_path' => $data['logo_path'] ?? $company->logo_path,
            ]);
        }

        return $company->fresh();
    }
}
