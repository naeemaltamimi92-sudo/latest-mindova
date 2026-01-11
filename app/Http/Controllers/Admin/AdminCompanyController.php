<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    /**
     * Display all companies.
     */
    public function index(Request $request)
    {
        $query = Company::with('user')
            ->withCount('challenges');

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('company_name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'challenges_count') {
            $query->orderBy('challenges_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

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
