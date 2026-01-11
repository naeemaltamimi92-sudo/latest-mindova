<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Route admin users to admin dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isVolunteer()) {
            return $this->volunteerDashboard($user);
        }

        if ($user->isCompany()) {
            return $this->companyDashboard($user);
        }

        return redirect()->route('login');
    }

    protected function volunteerDashboard($user)
    {
        $volunteer = $user->volunteer;

        if (!$volunteer) {
            return redirect()->route('complete-profile');
        }

        // Check if volunteer has signed the general NDA - MANDATORY
        if (!$volunteer->general_nda_signed) {
            return redirect()->route('nda.general')
                ->with('warning', 'Please sign the NDA to access the platform.');
        }

        // Load volunteer skills for profile display
        $volunteer->load('skills');

        $pendingAssignments = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'invited')
            ->with(['task.challenge'])
            ->get();

        $activeTasks = \App\Models\Task::whereHas('assignments', function ($q) use ($volunteer) {
            $q->where('volunteer_id', $volunteer->id)
                ->whereIn('invitation_status', ['accepted', 'auto_matched']);
        })
        ->with(['challenge', 'assignments'])
        ->get();

        // Get team invitations
        $teamInvitations = \App\Models\Team::whereHas('members', function ($q) use ($volunteer) {
            $q->where('volunteer_id', $volunteer->id)
                ->where('status', 'invited');
        })
        ->with(['challenge', 'leader.user', 'members'])
        ->get();

        // Get community education challenges - level 1-2 challenges in volunteer's field
        // Only show challenges if volunteer has a field set and challenge field matches
        $communityChallenges = collect();

        if ($volunteer->field) {
            $communityChallenges = \App\Models\Challenge::where('challenge_type', 'community_discussion')
                ->where('score', '>=', 1)
                ->where('score', '<=', 2)
                ->where('field', $volunteer->field) // Strict field matching
                ->with(['company', 'comments'])
                ->withCount([
                    'comments',
                    'comments as high_quality_comments_count' => function ($query) {
                        $query->where('ai_score', '>=', 7);
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }

        $stats = [
            'pending_assignments' => $pendingAssignments->count(),
            'active_tasks' => $activeTasks->count(),
            'completed_tasks' => TaskAssignment::where('volunteer_id', $volunteer->id)
                ->whereNotNull('completed_at')
                ->count(),
            'team_invitations' => $teamInvitations->count(),
        ];

        return view('dashboard.volunteer', compact('volunteer', 'pendingAssignments', 'activeTasks', 'teamInvitations', 'communityChallenges', 'stats'));
    }

    protected function companyDashboard($user)
    {
        $company = $user->company;

        if (!$company) {
            return redirect()->route('complete-profile');
        }

        // Get only the newest challenge for dashboard preview
        $newestChallenge = $company->challenges()->latest()->first();
        $challenges = $newestChallenge ? collect([$newestChallenge]) : collect();

        // Calculate progress and estimated time for each challenge
        foreach ($challenges as $challenge) {
            $totalTasks = $challenge->tasks()->count();

            if ($totalTasks > 0) {
                // Calculate progress percentage
                $completedTasks = $challenge->tasks()->where('status', 'completed')->count();
                $challenge->progress_percentage = round(($completedTasks / $totalTasks) * 100);

                // Calculate estimated remaining hours
                $remainingHours = $challenge->tasks()
                    ->whereNotIn('status', ['completed'])
                    ->sum('estimated_hours');
                $challenge->estimated_remaining_hours = $remainingHours;

                // Calculate total estimated hours for the challenge
                $challenge->total_estimated_hours = $challenge->tasks()->sum('estimated_hours');

                // Solution Progress Data
                $taskIds = $challenge->tasks()->pluck('id');

                // Count volunteers working on tasks
                $challenge->active_volunteers_count = \App\Models\TaskAssignment::whereIn('task_id', $taskIds)
                    ->whereIn('invitation_status', ['accepted', 'in_progress'])
                    ->distinct('volunteer_id')
                    ->count('volunteer_id');

                // Count invited volunteers (pending response)
                $challenge->invited_volunteers_count = \App\Models\TaskAssignment::whereIn('task_id', $taskIds)
                    ->where('invitation_status', 'invited')
                    ->distinct('volunteer_id')
                    ->count('volunteer_id');

                // Count submissions received
                $challenge->submissions_count = \App\Models\WorkSubmission::whereIn('task_id', $taskIds)->count();

                // Count approved/quality submissions
                $challenge->approved_submissions_count = \App\Models\WorkSubmission::whereIn('task_id', $taskIds)
                    ->where('solves_task', true)
                    ->count();

                // Average quality score of submissions
                $avgScore = \App\Models\WorkSubmission::whereIn('task_id', $taskIds)
                    ->whereNotNull('ai_quality_score')
                    ->avg('ai_quality_score');
                $challenge->avg_submission_quality = $avgScore ? round($avgScore, 1) : null;

                // Tasks with solutions
                $challenge->tasks_with_solutions = \App\Models\Task::whereIn('id', $taskIds)
                    ->whereHas('submissions', function($q) {
                        $q->where('solves_task', true);
                    })->count();
            } else {
                $challenge->progress_percentage = 0;
                $challenge->estimated_remaining_hours = 0;
                $challenge->total_estimated_hours = 0;
                $challenge->active_volunteers_count = 0;
                $challenge->invited_volunteers_count = 0;
                $challenge->submissions_count = 0;
                $challenge->approved_submissions_count = 0;
                $challenge->avg_submission_quality = null;
                $challenge->tasks_with_solutions = 0;
            }
        }

        // Get all task IDs for this company's challenges
        $companyTaskIds = \App\Models\Task::whereHas('challenge', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        })->pluck('id');

        $stats = [
            'active_challenges' => $company->challenges()->where('status', 'active')->count(),
            'tasks_in_progress' => \App\Models\Task::whereIn('id', $companyTaskIds)
                ->where('status', 'in_progress')->count(),
            'completed_tasks' => \App\Models\Task::whereIn('id', $companyTaskIds)
                ->where('status', 'completed')->count(),
            // Solution progress stats
            'total_volunteers_working' => \App\Models\TaskAssignment::whereIn('task_id', $companyTaskIds)
                ->whereIn('invitation_status', ['accepted', 'in_progress'])
                ->distinct('volunteer_id')
                ->count('volunteer_id'),
            'total_submissions' => \App\Models\WorkSubmission::whereIn('task_id', $companyTaskIds)->count(),
            'total_approved_solutions' => \App\Models\WorkSubmission::whereIn('task_id', $companyTaskIds)
                ->where('solves_task', true)->count(),
        ];

        return view('dashboard.company', compact('company', 'challenges', 'stats'));
    }
}
