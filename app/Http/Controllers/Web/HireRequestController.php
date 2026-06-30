<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\HireRequest;
use App\Models\HiringRecord;
use App\Models\Volunteer;
use App\Services\NotificationService;
use App\Services\TalentRankingService;
use Illuminate\Http\Request;

class HireRequestController extends Controller
{
    public function __construct(
        private readonly NotificationService  $notifications,
        private readonly TalentRankingService $ranking,
    ) {}

    /**
     * My hire requests (volunteer sees incoming; company sees outgoing).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->company) {
            $hireRequests = HireRequest::where('company_user_id', $user->id)
                ->with(['volunteer.user', 'volunteer.skills'])
                ->latest()
                ->paginate(20);
            $view = 'talent.hire-requests-company';
        } else {
            $volunteer    = $user->volunteer;
            $hireRequests = HireRequest::where('volunteer_id', $volunteer?->id)
                ->with(['company'])
                ->latest()
                ->paginate(20);
            $view = 'talent.hire-requests-volunteer';
        }

        return view($view, compact('hireRequests'));
    }

    /**
     * Show hire form (company → volunteer).
     */
    public function create(Request $request, Volunteer $volunteer)
    {
        abort_unless($request->user()->company, 403, 'Only companies can send hire requests.');

        $certificates = Certificate::where('user_id', $volunteer->user_id)
            ->where('company_confirmed', true)
            ->where('is_revoked', false)
            ->with('challenge')
            ->latest('issued_at')
            ->get();

        $score = $this->ranking->scoreBreakdown($volunteer);

        return view('talent.hire', compact('volunteer', 'certificates', 'score'));
    }

    /**
     * Store a new hire request.
     */
    public function store(Request $request, Volunteer $volunteer)
    {
        abort_unless($request->user()->company, 403, 'Only companies can send hire requests.');

        $validated = $request->validate([
            'position_title'       => 'required|string|max:200',
            'type'                 => 'required|in:full_time,part_time,consulting,project,invitation',
            'message'              => 'required|string|max:2000',
            'salary_range'         => 'nullable|string|max:100',
            'proposed_start_date'  => 'nullable|date|after:today',
            'is_private_challenge' => 'boolean',
        ]);

        // Prevent duplicate pending request
        $existing = HireRequest::where('company_user_id', $request->user()->id)
            ->where('volunteer_id', $volunteer->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending hire request with this professional.');
        }

        $hire = HireRequest::create([
            ...$validated,
            'company_user_id'      => $request->user()->id,
            'volunteer_id'         => $volunteer->id,
            'is_private_challenge' => $request->boolean('is_private_challenge'),
        ]);

        $this->notifications->send(
            user: $volunteer->user,
            type: 'hire_request_received',
            title: 'New Hire Request from ' . ($request->user()->company->company_name ?? $request->user()->name),
            message: "You received a {$hire->typeLabel()} invitation for the position: {$hire->position_title}.",
            actionUrl: route('hire-requests.index'),
        );

        return redirect()->route('talent.profile', $volunteer)
            ->with('success', 'Hire request sent successfully. The professional will be notified.');
    }

    /**
     * Volunteer accepts a hire request → creates HiringRecord.
     */
    public function accept(Request $request, HireRequest $hireRequest)
    {
        $volunteer = $request->user()->volunteer;
        abort_unless($hireRequest->volunteer_id === $volunteer?->id, 403);
        abort_unless($hireRequest->isPending(), 422, 'Request already responded to.');

        $hireRequest->update(['status' => 'converted', 'responded_at' => now()]);

        // Snapshot the volunteer's current verified history
        $certificates = Certificate::where('user_id', $volunteer->user_id)
            ->where('company_confirmed', true)
            ->where('is_revoked', false)
            ->pluck('id')
            ->toArray();

        $skills = $volunteer->skills->pluck('skill_name')->toArray();

        HiringRecord::create([
            'hire_request_id'          => $hireRequest->id,
            'company_user_id'          => $hireRequest->company_user_id,
            'volunteer_id'             => $volunteer->id,
            'position_title'           => $hireRequest->position_title,
            'engagement_type'          => in_array($hireRequest->type, ['full_time','part_time','consulting','project'])
                                            ? $hireRequest->type : 'project',
            'hired_at'                 => now(),
            'skills_used'              => $skills,
            'verified_certificate_ids' => $certificates,
            'professional_level'       => $volunteer->tier_name,
            'reputation_stars_at_hire' => $volunteer->stars ?? $volunteer->reputation_score,
            'trust_score_at_hire'      => $volunteer->trust_score ?? 100.0,
        ]);

        $this->notifications->send(
            user: $hireRequest->company,
            type: 'hire_request_accepted',
            title: $volunteer->user->name . ' Accepted Your Hire Request',
            message: "{$volunteer->user->name} accepted the {$hireRequest->typeLabel()} offer for \"{$hireRequest->position_title}\".",
            actionUrl: route('hire-requests.index'),
        );

        return back()->with('success', 'You accepted this hire request. A verified hiring record has been created.');
    }

    /**
     * Volunteer declines a hire request.
     */
    public function decline(Request $request, HireRequest $hireRequest)
    {
        $volunteer = $request->user()->volunteer;
        abort_unless($hireRequest->volunteer_id === $volunteer?->id, 403);
        abort_unless($hireRequest->isPending(), 422);

        $hireRequest->update(['status' => 'declined', 'responded_at' => now()]);

        $this->notifications->send(
            user: $hireRequest->company,
            type: 'hire_request_declined',
            title: 'Hire Request Declined',
            message: "{$volunteer->user->name} declined the offer for \"{$hireRequest->position_title}\".",
            actionUrl: route('hire-requests.index'),
        );

        return back()->with('info', 'Hire request declined.');
    }
}
