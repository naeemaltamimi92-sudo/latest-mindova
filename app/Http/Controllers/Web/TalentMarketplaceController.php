<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\HiringRecord;
use App\Models\Volunteer;
use App\Services\TalentRankingService;
use Illuminate\Http\Request;

class TalentMarketplaceController extends Controller
{
    public function __construct(private readonly TalentRankingService $ranking) {}

    public function index(Request $request)
    {
        $query = Volunteer::with(['user', 'skills'])
            ->whereHas('user', fn ($q) => $q->whereNotNull('email_verified_at'));

        // Keyword search (name or skills)
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('skills', fn ($s) => $s->where('skill_name', 'like', "%{$search}%"));
            });
        }

        // Tier / professional level
        if ($tier = $request->input('tier')) {
            $tierRanges = [
                'explorer'         => [0,   49],
                'contributor'      => [50,  199],
                'trusted_member'   => [200, 499],
                'expert_candidate' => [500, 1199],
                'certified_expert' => [1200, PHP_INT_MAX],
            ];
            if (isset($tierRanges[$tier])) {
                [$min, $max] = $tierRanges[$tier];
                $query->whereBetween('reputation_score', [$min, min($max, 99999)]);
            }
        }

        // Minimum stars
        if ($minStars = $request->integer('min_stars', 0)) {
            $query->where('reputation_score', '>=', $minStars);
        }

        // Minimum trust score
        if ($minTrust = $request->integer('min_trust', 0)) {
            $query->where('trust_score', '>=', $minTrust);
        }

        // Availability
        if ($minHours = $request->integer('min_hours', 0)) {
            $query->where('availability_hours_per_week', '>=', $minHours);
        }

        // Expert approved only
        if ($request->boolean('expert_only')) {
            $query->whereHas('user', function ($q) {
                $q->whereHas('certificates', fn ($c) => $c->whereNotNull('expert_approved_at'));
            });
        }

        // Verified projects minimum
        if ($minProjects = $request->integer('min_projects', 0)) {
            $query->whereHas('user', function ($q) use ($minProjects) {
                $q->withCount(['certificates as confirmed_certs_count' => function ($c) {
                    $c->where('company_confirmed', true)->where('is_revoked', false);
                }])->having('confirmed_certs_count', '>=', $minProjects);
            });
        }

        // Industry filter (via certificates)
        if ($industry = $request->input('industry')) {
            $query->whereHas('user.certificates', fn ($c) => $c->where('industry', $industry));
        }

        // NDA signed (verified & compliant)
        $query->where('general_nda_signed', true);

        $sort = $request->input('sort', 'ranking');

        // Eager-load confirmed certificate count for sort=projects to avoid N+1
        if ($sort === 'projects') {
            $query->withCount(['certificates as confirmed_certs_count' => fn ($c) =>
                $c->where('company_confirmed', true)->where('is_revoked', false)
            ]);
        }

        $volunteers = $query->get();

        $volunteers = match ($sort) {
            'stars'    => $volunteers->sortByDesc('reputation_score'),
            'trust'    => $volunteers->sortByDesc('trust_score'),
            'projects' => $volunteers->sortByDesc('confirmed_certs_count'),
            'recent'   => $volunteers->sortByDesc('updated_at'),
            default    => $this->ranking->rank($volunteers),
        };

        // Paginate manually (ranking is in-memory)
        $perPage   = 15;
        $page      = $request->integer('page', 1);
        $total     = $volunteers->count();
        $paginated = $volunteers->forPage($page, $perPage)->values();

        // Available industries for filter dropdown
        $industries = Certificate::whereNotNull('industry')
            ->select('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        return view('talent.marketplace', compact(
            'paginated', 'total', 'page', 'perPage', 'industries', 'sort'
        ));
    }

    /**
     * Talent profile view (hiring context — richer than public portfolio).
     */
    public function profile(Request $request, Volunteer $volunteer)
    {
        $volunteer->load(['user', 'skills']);

        $certificates = \App\Models\Certificate::where('user_id', $volunteer->user_id)
            ->where('company_confirmed', true)
            ->where('is_revoked', false)
            ->with(['challenge', 'expertVolunteer.user'])
            ->latest('issued_at')
            ->get();

        $hiringRecords = \App\Models\HiringRecord::where('volunteer_id', $volunteer->id)
            ->where('status', 'active')
            ->with('company')
            ->latest('hired_at')
            ->get();

        $score     = $this->ranking->scoreBreakdown($volunteer);
        $canHire   = $request->user()?->company !== null;
        $alreadySent = $request->user()
            ? \App\Models\HireRequest::where('company_user_id', $request->user()->id)
                ->where('volunteer_id', $volunteer->id)
                ->where('status', 'pending')
                ->exists()
            : false;

        return view('talent.profile', compact(
            'volunteer', 'certificates', 'hiringRecords', 'score', 'canHire', 'alreadySent'
        ));
    }

    public function verifyHire(Request $request)
    {
        $record = HiringRecord::where('hiring_verification_id', $request->input('id'))
            ->with(['company', 'volunteer.user'])
            ->firstOrFail();

        return view('talent.verify-hire', compact('record'));
    }
}
