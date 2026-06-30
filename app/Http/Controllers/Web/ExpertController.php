<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Challenge;
use App\Models\ExpertChallengeAssignment;
use App\Services\ExpertSelectionService;
use App\Services\NotificationService;
use App\Services\ReputationService;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function __construct(
        private readonly ReputationService    $reputation,
        private readonly NotificationService  $notifications,
        private readonly ExpertSelectionService $expertSelection,
    ) {}

    /**
     * Expert dashboard: all challenges this expert is assigned to.
     */
    public function dashboard(Request $request)
    {
        $volunteer = $request->user()->volunteer;

        abort_unless($volunteer && $volunteer->isExpertCandidate(), 403,
            'Expert access requires 500+ Stars.');

        $assignments = ExpertChallengeAssignment::where('volunteer_id', $volunteer->id)
            ->with(['challenge.company', 'challenge.tasks'])
            ->orderByRaw("FIELD(status,'active','accepted','invited','completed','declined') ASC")
            ->latest()
            ->get();

        $stats = [
            'total_assigned'    => $assignments->count(),
            'active'            => $assignments->whereIn('status', ['accepted', 'active'])->count(),
            'pending_invite'    => $assignments->where('status', 'invited')->count(),
            'completed'         => $assignments->where('status', 'completed')->count(),
            'pending_approval'  => Certificate::whereHas('challenge', function ($q) use ($volunteer) {
                    $q->whereHas('expertAssignments', fn($q2) =>
                        $q2->where('volunteer_id', $volunteer->id)->whereIn('status', ['accepted','active'])
                    );
                })
                ->whereNull('expert_approved_at')
                ->where('company_confirmed', true)
                ->count(),
        ];

        return view('expert.dashboard', compact('assignments', 'stats', 'volunteer'));
    }

    /**
     * Expert view of a specific challenge.
     */
    public function showChallenge(Request $request, Challenge $challenge)
    {
        $volunteer   = $request->user()->volunteer;
        $assignment  = ExpertChallengeAssignment::where('challenge_id', $challenge->id)
            ->where('volunteer_id', $volunteer->id)
            ->firstOrFail();

        $challenge->load([
            'company',
            'tasks.assignments.volunteer.user',
            'tasks.assignments.workSubmissions.reviews',
            'expertAssignments.volunteer.user',
        ]);

        // Certificates awaiting this expert's approval
        $pendingCerts = Certificate::where('challenge_id', $challenge->id)
            ->where('company_confirmed', true)
            ->whereNull('expert_approved_at')
            ->with('user')
            ->get();

        return view('expert.challenge', compact('challenge', 'assignment', 'pendingCerts', 'volunteer'));
    }

    /**
     * Accept an expert invitation.
     */
    public function acceptInvitation(Request $request, ExpertChallengeAssignment $assignment)
    {
        $volunteer = $request->user()->volunteer;
        abort_unless($assignment->volunteer_id === $volunteer->id, 403);
        abort_unless($assignment->status === 'invited', 422, 'Assignment already responded to.');

        $assignment->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        $this->notifications->send(
            user: $assignment->challenge->company->user ?? $assignment->challenge->ownerUser,
            type: 'expert_accepted',
            title: 'Expert Accepted Your Challenge',
            message: "{$volunteer->user->name} has accepted the expert role for \"{$assignment->challenge->title}\".",
            actionUrl: route('challenges.show', $assignment->challenge_id)
        );

        return back()->with('success', 'You have accepted this expert assignment.');
    }

    /**
     * Decline an expert invitation — system routes to next candidate.
     */
    public function declineInvitation(Request $request, ExpertChallengeAssignment $assignment)
    {
        $volunteer = $request->user()->volunteer;
        abort_unless($assignment->volunteer_id === $volunteer->id, 403);
        abort_unless($assignment->status === 'invited', 422);

        $assignment->update([
            'status'       => 'declined',
            'responded_at' => now(),
        ]);

        // Auto-route: find and invite the next best expert for this role
        $this->expertSelection->assignExpertsToChallenge($assignment->challenge);

        return back()->with('info', 'Invitation declined. Another expert will be contacted.');
    }

    /**
     * Expert approves a certificate — final seal before issuance.
     */
    public function approveCertificate(Request $request, Certificate $certificate)
    {
        $volunteer  = $request->user()->volunteer;
        abort_unless($volunteer && $volunteer->isExpertCandidate(), 403);

        $request->validate(['notes' => 'nullable|string|max:1000']);

        // Verify this expert is assigned to this challenge
        $assignment = ExpertChallengeAssignment::where('challenge_id', $certificate->challenge_id)
            ->where('volunteer_id', $volunteer->id)
            ->whereIn('status', ['accepted', 'active'])
            ->firstOrFail();

        // Stamp the certificate
        $hash = Certificate::makeVerificationHash($certificate);
        $certificate->update([
            'expert_id'           => $volunteer->id,
            'expert_approved_at'  => now(),
            'verification_hash'   => $hash,
        ]);

        // Award expert stars for approval
        $this->reputation->award($volunteer, 'mentored_junior', [
            'related_type' => Certificate::class,
            'related_id'   => $certificate->id,
        ]);

        // Notify the certificate holder
        $this->notifications->send(
            user: $certificate->user,
            type: 'certificate_expert_approved',
            title: '🎓 Your Certificate Was Approved by an Expert!',
            message: "Your verified professional certificate for \"{$certificate->challenge->title}\" has been approved by {$volunteer->user->name}.",
            actionUrl: route('certificates.show', $certificate->id)
        );

        return back()->with('success', 'Certificate approved and verification hash stamped.');
    }

    /**
     * Admin action: assign experts to a challenge manually.
     */
    public function assignExperts(Request $request, Challenge $challenge)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $assignments = $this->expertSelection->assignExpertsToChallenge($challenge);

        return back()->with('success', "Assigned {$assignments->count()} experts to this challenge.");
    }
}
