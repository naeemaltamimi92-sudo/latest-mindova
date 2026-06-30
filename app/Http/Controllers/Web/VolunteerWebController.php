<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Idea;
use App\Models\TaskAssignment;
use App\Models\Volunteer;

class VolunteerWebController extends Controller
{
    public function show($id)
    {
        $volunteer = Volunteer::with(['user', 'skills'])->findOrFail($id);

        // Verified certificates (the portfolio backbone)
        $certificates = Certificate::where('user_id', $volunteer->user_id)
            ->where('company_confirmed', true)
            ->where('is_revoked', false)
            ->with(['challenge', 'company', 'expertVolunteer.user'])
            ->latest('issued_at')
            ->get();

        // Completed task assignments
        $completedAssignments = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'completed')
            ->with(['task.challenge'])
            ->latest()
            ->limit(20)
            ->get();

        // Community ideas
        $ideas = Idea::where('volunteer_id', $volunteer->id)
            ->with('challenge')
            ->latest()
            ->limit(10)
            ->get();

        // Industry breakdown from certificates
        $industryBreakdown = $certificates
            ->whereNotNull('industry')
            ->groupBy('industry')
            ->map(fn($group) => $group->count())
            ->sortDesc();

        // Technology tags from all certificates
        $allTechnologies = $certificates->flatMap(fn($c) => $c->technologies ?? [])
            ->countBy()
            ->sortDesc()
            ->take(15);

        // Expert-approved certificates count
        $expertApprovedCount = $certificates->filter(fn($c) => $c->isExpertApproved())->count();

        // Success rate: completed tasks / total accepted tasks
        $totalAccepted  = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'completed'])
            ->count();
        $totalCompleted = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'completed')
            ->count();
        $successRate = $totalAccepted > 0 ? round(($totalCompleted / $totalAccepted) * 100) : 0;

        // Total verified hours (from certificates)
        $verifiedHours = (int) $certificates->sum('total_hours');

        $stats = [
            'verified_projects'   => $certificates->count(),
            'completed_tasks'     => $totalCompleted,
            'active_tasks'        => TaskAssignment::where('volunteer_id', $volunteer->id)
                                        ->whereIn('invitation_status', ['accepted', 'in_progress'])
                                        ->count(),
            'verified_hours'      => $verifiedHours,
            'expert_approved'     => $expertApprovedCount,
            'success_rate'        => $successRate,
            'ideas_count'         => $ideas->count(),
            'vote_points'         => $ideas->sum('community_votes'),
            'industries_count'    => $industryBreakdown->count(),
        ];

        return view('volunteers.show', compact(
            'volunteer', 'certificates', 'completedAssignments',
            'ideas', 'stats', 'industryBreakdown', 'allTechnologies'
        ));
    }
}
