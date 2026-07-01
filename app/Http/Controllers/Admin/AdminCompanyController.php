<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    use \App\Http\Controllers\Admin\Concerns\FiltersAdminIndex;

    /**
     * Display all companies.
     */
    public function index(Request $request)
    {
        $query = Company::with('user')
            ->withCount('challenges');

        // Search. Previously an unwrapped orWhere('company_name', ...)
        // chained after whereHas('user', ...), which applies to the WHOLE
        // query rather than just the search - harmless today since there's
        // no other top-level where() before it, but fragile. applySearch()
        // wraps the whole thing in one closure.
        $this->applySearch(
            $query,
            $request->filled('search') ? $request->search : null,
            ['company_name'],
            ['user' => ['name', 'email']]
        );

        // Sort
        $this->applySort(
            $query,
            $request,
            ['created_at', 'challenges_count', 'company_name'],
            'created_at'
        );

        $companies = $query->paginate(20);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Display a single company.
     */
    public function show(Company $company)
    {
        $company->load([
            'user',
            'challenges.workstreams.tasks',
        ]);

        // Count certificates issued for all challenges of this company
        $challengeIds = $company->challenges()->pluck('id');
        $certificatesIssued = \App\Models\Certificate::whereIn('challenge_id', $challengeIds)->count();

        $stats = [
            'total_challenges' => $company->challenges()->count(),
            'active_challenges' => $company->challenges()->where('status', 'active')->count(),
            'completed_challenges' => $company->challenges()->where('status', 'completed')->count(),
            'certificates_issued' => $certificatesIssued,
        ];

        return view('admin.companies.show', compact('company', 'stats'));
    }
}
