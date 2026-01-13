<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeAttachment;
use App\Jobs\AnalyzeChallengeBrief;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChallengeWebController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is a company - show their challenges
        if (auth()->check() && auth()->user()->isCompany()) {
            return $this->myCompanyChallenges($request);
        }

        $query = Challenge::with('company')->where('status', 'active');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('challenge_type', $request->type);
        }

        $challenges = $query->latest()->paginate(10);

        return view('challenges.index', compact('challenges'));
    }

    /**
     * Display all challenges for the logged-in company with filtering.
     */
    public function myCompanyChallenges(Request $request)
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('complete-profile');
        }

        $query = Challenge::where('company_id', $company->id)
            ->with(['tasks', 'workstreams']);

        // Status filter
        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->has('type') && $request->type && $request->type !== 'all') {
            $query->where('challenge_type', $request->type);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('refined_brief', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
            default:
                $query->latest();
        }

        $challenges = $query->paginate(12)->withQueryString();

        // Calculate stats for each challenge
        foreach ($challenges as $challenge) {
            $totalTasks = $challenge->tasks()->count();

            if ($totalTasks > 0) {
                $completedTasks = $challenge->tasks()->where('status', 'completed')->count();
                $challenge->progress_percentage = round(($completedTasks / $totalTasks) * 100);
                $challenge->total_tasks = $totalTasks;
                $challenge->completed_tasks = $completedTasks;
                $challenge->in_progress_tasks = $challenge->tasks()->where('status', 'in_progress')->count();
                $challenge->total_estimated_hours = $challenge->tasks()->sum('estimated_hours');

                // Solution stats
                $taskIds = $challenge->tasks()->pluck('id');
                $challenge->active_volunteers = \App\Models\TaskAssignment::whereIn('task_id', $taskIds)
                    ->whereIn('invitation_status', ['accepted', 'in_progress'])
                    ->distinct('volunteer_id')
                    ->count('volunteer_id');
                $challenge->submissions_count = \App\Models\WorkSubmission::whereIn('task_id', $taskIds)->count();
                $challenge->approved_count = \App\Models\WorkSubmission::whereIn('task_id', $taskIds)
                    ->where('solves_task', true)->count();
            } else {
                $challenge->progress_percentage = 0;
                $challenge->total_tasks = 0;
                $challenge->completed_tasks = 0;
                $challenge->in_progress_tasks = 0;
                $challenge->total_estimated_hours = 0;
                $challenge->active_volunteers = 0;
                $challenge->submissions_count = 0;
                $challenge->approved_count = 0;
            }
        }

        // Get overall stats
        $stats = [
            'total' => $company->challenges()->count(),
            'active' => $company->challenges()->where('status', 'active')->count(),
            'completed' => $company->challenges()->where('status', 'completed')->count(),
            'analyzing' => $company->challenges()->whereIn('status', ['submitted', 'analyzing'])->count(),
        ];

        return view('challenges.my-challenges', compact('challenges', 'company', 'stats'));
    }

    public function create()
    {
        if (!auth()->user()->isCompany()) {
            return redirect()->route('dashboard')->with('error', 'Only companies can submit challenges');
        }

        return view('challenges.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isCompany()) {
            return redirect()->route('dashboard')->with('error', 'Only companies can submit challenges');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:5000',
            'submission_deadline' => 'nullable|date|after:today',
            'completion_deadline' => 'nullable|date|after:submission_deadline',
        ]);

        // Create the challenge
        $challenge = Challenge::create([
            'company_id' => auth()->user()->company->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'original_description' => $validated['description'],
            'submission_deadline' => $validated['submission_deadline'] ?? null,
            'completion_deadline' => $validated['completion_deadline'] ?? null,
            'status' => 'submitted',
        ]);

        // Update company's total challenges count
        auth()->user()->company->increment('total_challenges_submitted');

        // Dispatch AI analysis job
        AnalyzeChallengeBrief::dispatch($challenge);

        // Notify admins about new challenge
        app(NotificationService::class)->notifyChallengeCreated($challenge);

        return redirect()->route('challenges.show', $challenge)
            ->with('success', 'Challenge submitted successfully! AI analysis is in progress...');
    }

    public function show(Challenge $challenge)
    {
        // Determine if current user can see spam (admins or challenge owner)
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $isOwner = auth()->check() && auth()->user()->isCompany() && auth()->user()->company?->id === $challenge->company_id;
        $canSeeSpam = $isAdmin || $isOwner;

        $challenge->load([
            'company',
            'workstreams.tasks.assignments.volunteer.user',
            'ideas' => function ($query) use ($canSeeSpam) {
                // Filter out spam ideas unless user is admin or challenge owner
                if (!$canSeeSpam) {
                    $query->where('is_spam', false);
                }
                $query->with('volunteer.user');
            },
            'teams.leader.user',
            'teams.members.volunteer.user',
            'challengeAnalyses',
            'attachments',
            'comments.user',
        ]);

        // Calculate statistics
        $stats = [
            'total_tasks' => 0,
            'completed_tasks' => 0,
            'in_progress_tasks' => 0,
            'total_hours' => 0,
            'active_volunteers' => 0,
            'total_ideas' => $challenge->ideas->count(),
            'total_teams' => $challenge->teams->count(),
        ];

        if ($challenge->challenge_type === 'team_execution') {
            $allTasks = $challenge->workstreams->flatMap->tasks;
            $stats['total_tasks'] = $allTasks->count();
            $stats['completed_tasks'] = $allTasks->where('status', 'completed')->count();
            $stats['in_progress_tasks'] = $allTasks->where('status', 'in_progress')->count();
            $stats['total_hours'] = $allTasks->sum('estimated_hours');

            // Get unique active volunteers
            $volunteerIds = collect();
            foreach ($allTasks as $task) {
                foreach ($task->assignments ?? [] as $assignment) {
                    if (in_array($assignment->invitation_status, ['accepted', 'in_progress'])) {
                        $volunteerIds->push($assignment->volunteer_id);
                    }
                }
            }
            $stats['active_volunteers'] = $volunteerIds->unique()->count();
        }

        // Get the latest analysis
        $latestAnalysis = $challenge->challengeAnalyses->sortByDesc('created_at')->first();

        // Get required skills from tasks
        $requiredSkills = collect();
        if ($challenge->challenge_type === 'team_execution') {
            foreach ($challenge->workstreams as $workstream) {
                foreach ($workstream->tasks as $task) {
                    if ($task->required_skills) {
                        $skills = is_array($task->required_skills) ? $task->required_skills : json_decode($task->required_skills, true);
                        if ($skills) {
                            $requiredSkills = $requiredSkills->merge($skills);
                        }
                    }
                }
            }
        }
        $requiredSkills = $requiredSkills->unique()->values();

        return view('challenges.show', compact('challenge', 'stats', 'latestAnalysis', 'requiredSkills', 'canSeeSpam'));
    }

    public function analytics(Challenge $challenge)
    {
        // Check if user is the owner
        if (!auth()->user()->isCompany() || auth()->user()->company->id !== $challenge->company_id) {
            abort(403, 'You do not have permission to view analytics for this challenge.');
        }

        $challenge->load(['workstreams.tasks.assignments.volunteer.user', 'ideas.volunteer.user']);

        // Calculate analytics
        $analytics = [
            'completion_rate' => 0,
            'active_contributors' => 0,
            'total_hours' => 0,
            'avg_response_time' => 0,
            'top_contributors' => [],
            'task_status_distribution' => [],
            'total_tasks' => 0,
            'top_ideas' => [],
            'total_ideas' => 0,
            'avg_ai_score' => 0,
            'total_votes' => 0,
            'participation_rate' => 0,
            'timeline' => [],
        ];

        if ($challenge->challenge_type === 'team_execution') {
            $allTasks = $challenge->workstreams->flatMap->tasks;
            $completedTasks = $allTasks->where('status', 'completed');

            $analytics['total_tasks'] = $allTasks->count();
            $analytics['completion_rate'] = $allTasks->count() > 0 ? round(($completedTasks->count() / $allTasks->count()) * 100) : 0;

            // Get all assignments
            $allAssignments = $allTasks->flatMap->assignments;
            $analytics['active_contributors'] = $allAssignments->where('invitation_status', 'in_progress')->pluck('volunteer_id')->unique()->count();
            $analytics['total_hours'] = $allAssignments->where('invitation_status', 'completed')->sum('actual_hours');

            // Top contributors
            $contributorsData = $allAssignments->where('invitation_status', 'completed')
                ->groupBy('volunteer_id')
                ->map(function($assignments) {
                    $volunteer = $assignments->first()->volunteer;
                    return [
                        'volunteer_id' => $volunteer->id,
                        'name' => $volunteer->user->name,
                        'completed_tasks' => $assignments->count(),
                        'hours' => $assignments->sum('actual_hours'),
                    ];
                })
                ->sortByDesc('hours')
                ->take(5)
                ->values();

            $analytics['top_contributors'] = $contributorsData->toArray();

            // Task status distribution
            $analytics['task_status_distribution'] = $allTasks->groupBy('status')->map->count()->toArray();

            // Timeline
            $analytics['timeline'] = [
                [
                    'type' => 'created',
                    'title' => 'Challenge Created',
                    'description' => 'Challenge was submitted for analysis',
                    'date' => $challenge->created_at->format('M d, Y H:i'),
                ],
                [
                    'type' => 'analyzed',
                    'title' => 'AI Analysis Complete',
                    'description' => 'Challenge was refined and decomposed into tasks',
                    'date' => $challenge->updated_at->format('M d, Y H:i'),
                ],
            ];

        } else {
            // Community discussion analytics
            $ideas = $challenge->ideas;
            $scoredIdeas = $ideas->where('status', 'scored');

            $analytics['total_ideas'] = $ideas->count();
            $analytics['avg_ai_score'] = $scoredIdeas->avg('ai_score') ?? 0;
            $analytics['total_votes'] = $scoredIdeas->sum('community_votes');
            $analytics['top_ideas'] = $scoredIdeas->sortByDesc('final_score')->take(5);
            $analytics['participation_rate'] = $ideas->pluck('volunteer_id')->unique()->count();

            // Timeline
            $analytics['timeline'] = [
                [
                    'type' => 'created',
                    'title' => 'Challenge Created',
                    'description' => 'Challenge opened for community ideas',
                    'date' => $challenge->created_at->format('M d, Y H:i'),
                ],
                [
                    'type' => 'ideas',
                    'title' => 'Ideas Submitted',
                    'description' => "{$analytics['total_ideas']} ideas submitted by the community",
                    'date' => $challenge->updated_at->format('M d, Y H:i'),
                ],
            ];
        }

        return view('challenges.analytics', compact('challenge', 'analytics'));
    }

    /**
     * Show the form for editing a challenge.
     */
    public function edit(Challenge $challenge)
    {
        // Check ownership
        if (!auth()->user()->isCompany() || auth()->user()->company->id !== $challenge->company_id) {
            abort(403, 'You do not have permission to edit this challenge.');
        }

        // Check if challenge can be edited
        if (in_array($challenge->status, ['completed', 'delivered'])) {
            return redirect()->route('challenges.show', $challenge)
                ->with('error', 'This challenge cannot be edited because it has been completed.');
        }

        // Check for active tasks
        $hasActiveTasks = $challenge->tasks()->whereIn('status', ['in_progress', 'completed'])->exists();
        if ($hasActiveTasks) {
            return redirect()->route('challenges.show', $challenge)
                ->with('error', 'This challenge cannot be edited because it has active or completed tasks.');
        }

        $challenge->load('attachments');

        return view('challenges.edit', compact('challenge'));
    }

    /**
     * Update a challenge.
     */
    public function update(Request $request, Challenge $challenge)
    {
        // Check ownership
        if (!auth()->user()->isCompany() || auth()->user()->company->id !== $challenge->company_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to edit this challenge.'], 403);
            }
            abort(403, 'You do not have permission to edit this challenge.');
        }

        // Check if challenge can be edited
        if (in_array($challenge->status, ['completed', 'delivered'])) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This challenge cannot be edited because it has been completed.'], 422);
            }
            return redirect()->route('challenges.show', $challenge)
                ->with('error', 'This challenge cannot be edited because it has been completed.');
        }

        // Check for active tasks
        $hasActiveTasks = $challenge->tasks()->whereIn('status', ['in_progress', 'completed'])->exists();
        if ($hasActiveTasks) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This challenge cannot be edited because it has active or completed tasks.'], 422);
            }
            return redirect()->route('challenges.show', $challenge)
                ->with('error', 'This challenge cannot be edited because it has active or completed tasks.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:5000',
            'submission_deadline' => 'nullable|date',
            'completion_deadline' => 'nullable|date|after_or_equal:submission_deadline',
            'attachments.*' => 'nullable|file|max:10240',
            'remove_attachments' => 'nullable|string',
        ]);

        // Handle attachment removal
        if (!empty($request->remove_attachments)) {
            $attachmentIdsToRemove = array_filter(explode(',', $request->remove_attachments));
            foreach ($attachmentIdsToRemove as $attachmentId) {
                $attachment = ChallengeAttachment::where('id', $attachmentId)
                    ->where('challenge_id', $challenge->id)
                    ->first();
                if ($attachment) {
                    Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                }
            }
        }

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('challenge-attachments/' . $challenge->id, 'public');

                ChallengeAttachment::create([
                    'challenge_id' => $challenge->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Update the challenge
        $challenge->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'original_description' => $validated['description'],
            'submission_deadline' => $validated['submission_deadline'] ?? null,
            'completion_deadline' => $validated['completion_deadline'] ?? null,
            'status' => 'submitted',
            'ai_analysis_status' => 'pending',
            'rejection_reason' => null,
            'refined_brief' => null,
            'score' => null,
            'challenge_type' => null,
        ]);

        // Delete old analysis records
        $challenge->challengeAnalyses()->delete();

        // Dispatch AI analysis job
        AnalyzeChallengeBrief::dispatch($challenge);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Challenge updated and resubmitted for analysis.',
            ]);
        }

        return redirect()->route('challenges.show', $challenge)
            ->with('success', 'Challenge updated successfully! AI analysis is in progress...');
    }

    /**
     * Delete a challenge.
     */
    public function destroy(Request $request, Challenge $challenge)
    {
        // Check ownership
        if (!auth()->user()->isCompany() || auth()->user()->company->id !== $challenge->company_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to delete this challenge.'], 403);
            }
            abort(403, 'You do not have permission to delete this challenge.');
        }

        // Check if challenge can be deleted
        if (in_array($challenge->status, ['completed', 'delivered'])) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This challenge cannot be deleted because it has been completed.'], 422);
            }
            return redirect()->route('challenges.index')
                ->with('error', 'This challenge cannot be deleted because it has been completed.');
        }

        // Check for active tasks
        $hasActiveTasks = $challenge->tasks()->whereIn('status', ['in_progress', 'completed'])->exists();
        if ($hasActiveTasks) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This challenge cannot be deleted because it has active or completed tasks.'], 422);
            }
            return redirect()->route('challenges.index')
                ->with('error', 'This challenge cannot be deleted because it has active or completed tasks.');
        }

        // Delete attachments from storage
        foreach ($challenge->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete related records
        $challenge->attachments()->delete();
        $challenge->challengeAnalyses()->delete();
        $challenge->comments()->delete();

        // Delete workstreams and tasks
        foreach ($challenge->workstreams as $workstream) {
            foreach ($workstream->tasks as $task) {
                $task->assignments()->delete();
            }
            $workstream->tasks()->delete();
        }
        $challenge->workstreams()->delete();

        // Delete the challenge
        $challenge->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Challenge deleted successfully.',
            ]);
        }

        return redirect()->route('challenges.index')
            ->with('success', 'Challenge deleted successfully.');
    }
}
