<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Challenge;
use App\Models\WorkSubmission;

class CompanyWebController extends Controller
{
    /**
     * Show work submissions for the company's challenges.
     */
    public function submissions()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            abort(403, 'Company profile not found');
        }

        // Get all challenge IDs for this company
        $challengeIds = Challenge::where('company_id', $company->id)->pluck('id');

        // Get all submissions for the company's challenges
        $submissions = WorkSubmission::whereHas('task', function ($query) use ($challengeIds) {
            $query->whereIn('challenge_id', $challengeIds);
        })
        ->with([
            'task.challenge',
            'task.workstream',
            'volunteer.user',
            'taskAssignment',
            'reviews'
        ])
        ->orderByDesc('submitted_at')
        ->paginate(15);

        // Group submissions by status for stats
        $stats = [
            'total' => WorkSubmission::whereHas('task', fn($q) => $q->whereIn('challenge_id', $challengeIds))->count(),
            'pending' => WorkSubmission::whereHas('task', fn($q) => $q->whereIn('challenge_id', $challengeIds))->where('status', 'submitted')->count(),
            'under_review' => WorkSubmission::whereHas('task', fn($q) => $q->whereIn('challenge_id', $challengeIds))->where('status', 'under_review')->count(),
            'approved' => WorkSubmission::whereHas('task', fn($q) => $q->whereIn('challenge_id', $challengeIds))->where('status', 'approved')->count(),
            'revision_requested' => WorkSubmission::whereHas('task', fn($q) => $q->whereIn('challenge_id', $challengeIds))->where('status', 'revision_requested')->count(),
            'rejected' => WorkSubmission::whereHas('task', fn($q) => $q->whereIn('challenge_id', $challengeIds))->where('status', 'rejected')->count(),
        ];

        return view('company.submissions.index', compact('submissions', 'stats', 'company'));
    }

    /**
     * Show a single submission for review.
     */
    public function showSubmission(WorkSubmission $submission)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            abort(403, 'Company profile not found');
        }

        // Verify this submission belongs to one of the company's challenges
        $challengeIds = Challenge::where('company_id', $company->id)->pluck('id');

        if (!$submission->task || !in_array($submission->task->challenge_id, $challengeIds->toArray())) {
            abort(403, 'You do not have access to this submission');
        }

        $submission->load([
            'task.challenge',
            'task.workstream',
            'volunteer.user',
            'volunteer.skills',
            'taskAssignment',
            'reviews.reviewer'
        ]);

        return view('company.submissions.show', compact('submission', 'company'));
    }

    /**
     * Review a submission (approve, reject, request revision).
     */
    public function reviewSubmission(\Illuminate\Http\Request $request, WorkSubmission $submission)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            abort(403, 'Company profile not found');
        }

        // Verify ownership
        $challengeIds = Challenge::where('company_id', $company->id)->pluck('id');

        if (!$submission->task || !in_array($submission->task->challenge_id, $challengeIds->toArray())) {
            abort(403, 'You do not have access to this submission');
        }

        $request->validate([
            'decision' => 'required|in:approved,rejected,revision_requested',
            'feedback' => 'required|string|min:10',
            'quality_score' => 'nullable|integer|min:1|max:100',
            'timeliness_score' => 'nullable|integer|min:1|max:100',
            'communication_score' => 'nullable|integer|min:1|max:100',
        ]);

        // Create review
        \App\Models\Review::create([
            'work_submission_id' => $submission->id,
            'reviewer_id' => $user->id,
            'rating' => $request->decision === 'approved' ? 5 : ($request->decision === 'revision_requested' ? 3 : 2),
            'quality_score' => $request->quality_score ?? 0,
            'timeliness_score' => $request->timeliness_score ?? 0,
            'communication_score' => $request->communication_score ?? 0,
            'feedback' => $request->feedback,
            'decision' => $request->decision,
            'revision_notes' => $request->decision === 'revision_requested' ? $request->feedback : null,
        ]);

        // Update submission status
        $submission->update([
            'status' => $request->decision,
            'reviewed_at' => now(),
        ]);

        // If approved, update task assignment status
        if ($request->decision === 'approved' && $submission->taskAssignment) {
            $submission->taskAssignment->update([
                'invitation_status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        $statusMessages = [
            'approved' => 'Submission approved successfully!',
            'rejected' => 'Submission has been rejected.',
            'revision_requested' => 'Revision request sent to the volunteer.',
        ];

        return redirect()->route('company.submissions.index')
            ->with('success', $statusMessages[$request->decision]);
    }
    /**
     * Show company public profile.
     */
    public function show($id)
    {
        $company = Company::with('user')->findOrFail($id);

        // Get active challenges
        $activeChallenges = Challenge::where('company_id', $company->id)
            ->where('status', 'active')
            ->with(['workstreams.tasks', 'ideas'])
            ->latest()
            ->get();

        // Get completed challenges
        $completedChallenges = Challenge::where('company_id', $company->id)
            ->where('status', 'completed')
            ->with(['workstreams.tasks', 'ideas'])
            ->latest()
            ->limit(10)
            ->get();

        // Calculate stats
        $allChallenges = Challenge::where('company_id', $company->id)->get();

        $stats = [
            'total_challenges' => $allChallenges->count(),
            'active_challenges' => $allChallenges->where('status', 'active')->count(),
            'completed_challenges' => $allChallenges->where('status', 'completed')->count(),
            'total_tasks' => $allChallenges->sum(function($challenge) {
                return $challenge->workstreams->sum(function($workstream) {
                    return $workstream->tasks->count();
                });
            }),
            'total_ideas' => $allChallenges->sum(function($challenge) {
                return $challenge->ideas->count();
            }),
            'team_challenges' => $allChallenges->where('challenge_type', 'team_execution')->count(),
            'community_challenges' => $allChallenges->where('challenge_type', 'community_discussion')->count(),
            'complexity_distribution' => $allChallenges->groupBy('complexity_level')->map->count()->sortKeys(),
        ];

        return view('companies.show', compact('company', 'activeChallenges', 'completedChallenges', 'stats'));
    }
}
