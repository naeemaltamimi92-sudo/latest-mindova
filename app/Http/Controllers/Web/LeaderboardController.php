<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\Company;
use App\Models\TaskAssignment;
use App\Models\Idea;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Show leaderboard.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'reputation');

        // Get volunteers based on filter
        $volunteers = $this->getVolunteers($filter);

        // Get top companies
        $topCompanies = Company::withCount('challenges')
            ->with('challenges.workstreams.tasks')
            ->get()
            ->map(function($company) {
                $company->tasks_count = $company->challenges->sum(function($challenge) {
                    return $challenge->workstreams->sum(function($workstream) {
                        return $workstream->tasks->count();
                    });
                });
                return $company;
            })
            ->sortByDesc('challenges_count')
            ->take(6);

        return view('leaderboard.index', compact('volunteers', 'topCompanies', 'filter'));
    }

    /**
     * Get volunteers sorted by different criteria.
     */
    private function getVolunteers($filter)
    {
        switch ($filter) {
            case 'tasks':
                return Volunteer::with(['user', 'skills'])
                    ->withCount(['assignments as completed_tasks_count' => function ($query) {
                        $query->where('invitation_status', 'completed');
                    }])
                    ->orderBy('completed_tasks_count', 'desc')
                    ->limit(50)
                    ->get();

            case 'hours':
                return Volunteer::with(['user', 'skills'])
                    ->leftJoin('task_assignments', function($join) {
                        $join->on('volunteers.id', '=', 'task_assignments.volunteer_id')
                             ->where('task_assignments.invitation_status', '=', 'completed');
                    })
                    ->select('volunteers.*', DB::raw('SUM(task_assignments.actual_hours) as total_hours'))
                    ->groupBy('volunteers.id')
                    ->orderBy('total_hours', 'desc')
                    ->limit(50)
                    ->get();

            case 'ideas':
                return Volunteer::with(['user', 'skills'])
                    ->leftJoin('ideas', function($join) {
                        $join->on('volunteers.id', '=', 'ideas.volunteer_id')
                             ->where('ideas.status', '=', 'scored');
                    })
                    ->select('volunteers.*', DB::raw('AVG(ideas.final_score) as avg_idea_score'))
                    ->groupBy('volunteers.id')
                    ->having('avg_idea_score', '>', 0)
                    ->orderBy('avg_idea_score', 'desc')
                    ->limit(50)
                    ->get();

            case 'reputation':
            default:
                return Volunteer::with(['user', 'skills'])
                    ->orderBy('reputation_score', 'desc')
                    ->limit(50)
                    ->get();
        }
    }
}
