<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class TaskWebController extends Controller
{
    /**
     * Show available tasks for volunteers.
     */
    public function available(Request $request)
    {
        $user = $request->user();

        if (!$user->isVolunteer() || !$user->volunteer) {
            return redirect()->route('dashboard')
                ->with('error', 'Only volunteers can view available tasks.');
        }

        $volunteer = $user->volunteer;

        // Build query for available tasks
        $query = Task::with(['challenge', 'workstream'])
            ->where('status', 'open')
            ->whereDoesntHave('assignments', function ($q) use ($volunteer) {
                $q->where('volunteer_id', $volunteer->id);
            });

        // Apply filters
        if ($request->has('skill') && $request->skill) {
            $query->where(function ($q) use ($request) {
                $q->whereJsonContains('required_skills', $request->skill)
                  ->orWhereJsonContains('preferred_skills', $request->skill);
            });
        }

        if ($request->has('complexity_max') && $request->complexity_max) {
            $query->where('complexity_score', '<=', $request->complexity_max);
        }

        $tasks = $query->latest()->paginate(15);

        // Get unique skills from all tasks for filter dropdown
        $allSkills = Task::where('status', 'open')
            ->get()
            ->pluck('required_skills')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('tasks.available', compact('tasks', 'allSkills'));
    }

    /**
     * Show task details.
     */
    public function show(Request $request, Task $task)
    {
        $task->load(['challenge', 'workstream', 'assignments.volunteer.user']);

        // Get current user's assignment if exists
        $myAssignment = null;
        if ($request->user()->isVolunteer() && $request->user()->volunteer) {
            $myAssignment = TaskAssignment::where('task_id', $task->id)
                ->where('volunteer_id', $request->user()->volunteer->id)
                ->first();
        }

        return view('tasks.show', compact('task', 'myAssignment'));
    }
}
