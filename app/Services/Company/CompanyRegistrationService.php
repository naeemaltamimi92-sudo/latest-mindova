<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Models\User;

class CompanyRegistrationService
{
    public function completeProfile(User $user, array $data): Company
    {
        if (!$user->isCompany()) {
            $user->update(['user_type' => 'company']);
        }

        $company = Company::create([
            'user_id'                    => $user->id,
            'company_name'               => $data['company_name'],
            'industry'                   => $data['industry'] ?? null,
            'website'                    => $data['website'] ?? null,
            'description'                => $data['description'] ?? null,
            'logo_path'                  => $data['logo_path'] ?? null,
            'total_challenges_submitted' => 0,
        ]);

        return $company;
    }

    public function updateProfile(Company $company, array $data): Company
    {
        $company->update([
            'company_name' => $data['company_name'] ?? $company->company_name,
            'industry'     => $data['industry'] ?? $company->industry,
            'website'      => $data['website'] ?? $company->website,
            'description'  => $data['description'] ?? $company->description,
            'logo_path'    => $data['logo_path'] ?? $company->logo_path,
        ]);

        return $company->fresh();
    }
}
