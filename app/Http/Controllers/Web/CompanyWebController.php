<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Challenge;
use App\Models\WorkSubmission;
use App\Models\ReputationHistory;
use App\Jobs\AggregateChallengeCompletion;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

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

        // Build validation rules - quality_score required for approval
        $rules = [
            'decision' => 'required|in:approved,rejected,revision_requested',
            'feedback' => 'required|string|min:10',
            'timeliness_score' => 'nullable|integer|min:1|max:100',
            'communication_score' => 'nullable|integer|min:1|max:100',
        ];

        // Quality score is required when approving
        if ($request->decision === 'approved') {
            $rules['quality_score'] = 'required|integer|min:1|max:100';
        } else {
            $rules['quality_score'] = 'nullable|integer|min:1|max:100';
        }

        $request->validate($rules);

        $qualityScore = $request->quality_score ?? 0;

        // Create review
        \App\Models\Review::create([
            'work_submission_id' => $submission->id,
            'reviewer_id' => $user->id,
            'rating' => $request->decision === 'approved' ? 5 : ($request->decision === 'revision_requested' ? 3 : 2),
            'quality_score' => $qualityScore,
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
            'ai_quality_score' => $request->decision === 'approved' ? $qualityScore : $submission->ai_quality_score,
        ]);

        $notificationService = app(NotificationService::class);

        // Handle different decisions
        if ($request->decision === 'approved') {
            $this->handleApproval($submission, $qualityScore, $notificationService);
        } elseif ($request->decision === 'revision_requested') {
            $this->handleRevisionRequest($submission, $notificationService);
        }
        // For rejection, no additional actions needed

        // Award reputation points
        $this->awardReputationPoints($submission, $request->decision, $qualityScore);

        $statusMessages = [
            'approved' => 'Submission approved successfully!',
            'rejected' => 'Submission has been rejected.',
            'revision_requested' => 'Revision request sent to the volunteer.',
        ];

        return redirect()->route('company.submissions.index')
            ->with('success', $statusMessages[$request->decision]);
    }

    /**
     * Handle approval of a submission.
     */
    protected function handleApproval(WorkSubmission $submission, int $qualityScore, NotificationService $notificationService): void
    {
        $assignment = $submission->taskAssignment;
        $volunteer = $submission->volunteer;
        $task = $submission->task;

        // Update task assignment status
        if ($assignment) {
            $assignment->update([
                'invitation_status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        // Update volunteer stats
        if ($volunteer) {
            $volunteer->increment('total_tasks_completed');
            $volunteer->increment('total_hours_contributed', $submission->hours_worked ?? 0);
        }

        // Check if all task assignments are complete, then mark task as completed
        if ($task) {
            $pendingAssignments = $task->assignments()
                ->whereIn('invitation_status', ['invited', 'accepted', 'in_progress', 'submitted'])
                ->count();

            if ($pendingAssignments === 0) {
                $task->update(['status' => 'completed']);
            }
        }

        // Send approval notification to volunteer
        if ($volunteer && $volunteer->user) {
            $notificationService->send(
                user: $volunteer->user,
                type: 'submission_approved',
                title: 'Submission Approved!',
                message: "Your submission for \"{$task->title}\" has been approved with a quality score of {$qualityScore}/100. Great work!",
                actionUrl: "/tasks/{$task->id}"
            );
        }

        // Check challenge completion
        $this->checkChallengeCompletion($submission);
    }

    /**
     * Handle revision request for a submission.
     */
    protected function handleRevisionRequest(WorkSubmission $submission, NotificationService $notificationService): void
    {
        $assignment = $submission->taskAssignment;
        $volunteer = $submission->volunteer;
        $task = $submission->task;

        // Reset task assignment to in_progress status
        if ($assignment) {
            $assignment->update([
                'invitation_status' => 'in_progress',
                'submitted_at' => null,
            ]);
        }

        // Send revision notification to volunteer
        if ($volunteer && $volunteer->user) {
            $notificationService->send(
                user: $volunteer->user,
                type: 'revision_requested',
                title: 'Revision Requested',
                message: "The company has requested revisions for your submission on \"{$task->title}\". Please review the feedback and resubmit.",
                actionUrl: "/tasks/{$task->id}"
            );
        }
    }

    /**
     * Award reputation points based on the review decision.
     */
    protected function awardReputationPoints(WorkSubmission $submission, string $decision, int $qualityScore): void
    {
        $volunteer = $submission->volunteer;
        if (!$volunteer) {
            return;
        }

        $points = 0;
        $reason = '';

        if ($decision === 'approved') {
            // Award points based on quality score
            if ($qualityScore >= 90) {
                $points = 20;
                $reason = 'Excellent submission approved (90%+ quality)';
            } elseif ($qualityScore >= 80) {
                $points = 15;
                $reason = 'Great submission approved (80%+ quality)';
            } elseif ($qualityScore >= 70) {
                $points = 10;
                $reason = 'Good submission approved (70%+ quality)';
            } else {
                $points = 5;
                $reason = 'Submission approved';
            }
        } elseif ($decision === 'revision_requested') {
            // Small points for effort
            $points = 2;
            $reason = 'Submission effort (revision requested)';
        }
        // No points for rejection

        if ($points > 0) {
            $newTotal = ($volunteer->reputation_score ?? 50) + $points;

            // Update volunteer's reputation score
            $volunteer->update(['reputation_score' => $newTotal]);

            // Record reputation history
            ReputationHistory::create([
                'volunteer_id' => $volunteer->id,
                'change_amount' => $points,
                'new_total' => $newTotal,
                'reason' => $reason,
                'related_type' => WorkSubmission::class,
                'related_id' => $submission->id,
                'created_at' => now(),
            ]);

            Log::info('Reputation points awarded', [
                'volunteer_id' => $volunteer->id,
                'points' => $points,
                'new_total' => $newTotal,
                'reason' => $reason,
            ]);
        }
    }

    /**
     * Check if all tasks in a challenge are complete and trigger aggregation.
     */
    protected function checkChallengeCompletion(WorkSubmission $submission): void
    {
        $task = $submission->task;
        if (!$task || !$task->challenge) {
            return;
        }

        $challenge = $task->challenge;

        // Get all tasks for this challenge
        $allTasks = $challenge->tasks;
        $totalTasks = $allTasks->count();

        if ($totalTasks === 0) {
            return;
        }

        // Count tasks with at least one approved submission
        $completedTasksCount = 0;
        foreach ($allTasks as $challengeTask) {
            $hasApprovedSubmission = WorkSubmission::where('task_id', $challengeTask->id)
                ->where('status', 'approved')
                ->exists();

            if ($hasApprovedSubmission) {
                $completedTasksCount++;
            }
        }

        Log::info('Challenge completion check', [
            'challenge_id' => $challenge->id,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasksCount,
        ]);

        // If all tasks have at least one approved submission, dispatch aggregation job
        if ($completedTasksCount >= $totalTasks) {
            Log::info('All tasks completed, dispatching aggregation job', [
                'challenge_id' => $challenge->id,
            ]);

            AggregateChallengeCompletion::dispatch($challenge);
        }
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
