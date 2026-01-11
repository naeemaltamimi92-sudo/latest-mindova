<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get all tasks with filters.
     */
    public function index(Request $request)
    {
        $query = Task::with(['workstream', 'challenge', 'assignments']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by challenge
        if ($request->has('challenge_id')) {
            $query->where('challenge_id', $request->challenge_id);
        }

        // Filter by workstream
        if ($request->has('workstream_id')) {
            $query->where('workstream_id', $request->workstream_id);
        }

        // Filter by complexity
        if ($request->has('complexity_min')) {
            $query->where('complexity_score', '>=', $request->complexity_min);
        }
        if ($request->has('complexity_max')) {
            $query->where('complexity_score', '<=', $request->complexity_max);
        }

        // Filter by required skills
        if ($request->has('skill')) {
            $query->whereJsonContains('required_skills', $request->skill);
        }

        $tasks = $query->latest()->paginate(20);

        return TaskResource::collection($tasks);
    }

    /**
     * Get a specific task.
     */
    public function show(Task $task)
    {
        $task->load([
            'workstream',
            'challenge.company',
            'assignments.volunteer.user',
        ]);

        return response()->json([
            'task' => new TaskResource($task),
        ]);
    }

    /**
     * Get available tasks for a volunteer (based on skills).
     */
    public function availableForVolunteer(Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Only volunteers can view available tasks',
            ], 403);
        }

        $volunteer = $user->volunteer->load('skills');

        // Get volunteer's skill names
        $volunteerSkills = $volunteer->skills->pluck('skill_name')->toArray();

        // Find tasks where volunteer has matching skills
        $query = Task::with(['workstream', 'challenge', 'assignments'])
            ->whereIn('status', ['pending', 'matching'])
            ->whereHas('challenge', function ($q) {
                $q->where('status', 'active');
            });

        // Filter by skill match
        if (!empty($volunteerSkills)) {
            $query->where(function ($q) use ($volunteerSkills) {
                foreach ($volunteerSkills as $skill) {
                    $q->orWhereJsonContains('required_skills', $skill);
                }
            });
        }

        // Exclude tasks already assigned to this volunteer
        $query->whereDoesntHave('assignments', function ($q) use ($volunteer) {
            $q->where('volunteer_id', $volunteer->id)
                ->whereIn('invitation_status', ['pending', 'accepted', 'in_progress']);
        });

        $tasks = $query->limit(20)->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Get tasks assigned to the authenticated volunteer.
     */
    public function myTasks(Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Only volunteers can view their tasks',
            ], 403);
        }

        $tasks = Task::whereHas('assignments', function ($q) use ($user) {
            $q->where('volunteer_id', $user->volunteer->id)
                ->whereIn('invitation_status', ['pending', 'accepted', 'in_progress']);
        })
        ->with(['workstream', 'challenge', 'assignments' => function ($q) use ($user) {
            $q->where('volunteer_id', $user->volunteer->id);
        }])
        ->latest()
        ->paginate(20);

        return TaskResource::collection($tasks);
    }
}
