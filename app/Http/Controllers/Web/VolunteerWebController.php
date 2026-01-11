<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\TaskAssignment;
use App\Models\Idea;

class VolunteerWebController extends Controller
{
    /**
     * Show volunteer public profile.
     */
    public function show($id)
    {
        $volunteer = Volunteer::with(['user', 'skills'])->findOrFail($id);

        // Get completed assignments
        $completedAssignments = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'completed')
            ->with(['task.challenge'])
            ->latest()
            ->limit(10)
            ->get();

        // Get ideas
        $ideas = Idea::where('volunteer_id', $volunteer->id)
            ->with('challenge')
            ->latest()
            ->limit(10)
            ->get();

        // Calculate stats
        $stats = [
            'completed_tasks' => TaskAssignment::where('volunteer_id', $volunteer->id)
                ->where('invitation_status', 'completed')
                ->count(),
            'active_tasks' => TaskAssignment::where('volunteer_id', $volunteer->id)
                ->whereIn('invitation_status', ['accepted', 'in_progress'])
                ->count(),
            'total_hours' => TaskAssignment::where('volunteer_id', $volunteer->id)
                ->where('invitation_status', 'completed')
                ->sum('actual_hours'),
            'ideas_count' => $ideas->count(),
            'rating_points' => 0, // TODO: Implement ratings
            'vote_points' => $ideas->sum('community_votes'),
        ];

        return view('volunteers.show', compact('volunteer', 'completedAssignments', 'ideas', 'stats'));
    }
}
