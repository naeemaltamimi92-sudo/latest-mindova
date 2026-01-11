<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskAssignmentResource;
use App\Models\TaskAssignment;
use App\Models\WorkSubmission;
use App\Models\ChallengeNdaSigning;
use App\Services\NotificationService;
use App\Jobs\AnalyzeSolutionQuality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskAssignmentController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}
    /**
     * Get all assignments for a task.
     */
    public function index(Request $request)
    {
        $query = TaskAssignment::with(['task', 'volunteer.user']);

        // Filter by task
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Filter by volunteer
        if ($request->has('volunteer_id')) {
            $query->where('volunteer_id', $request->volunteer_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('invitation_status', $request->status);
        }

        $assignments = $query->latest()->paginate(20);

        return TaskAssignmentResource::collection($assignments);
    }

    /**
     * Get pending assignments for the authenticated volunteer.
     */
    public function pending(Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Only volunteers can view assignments',
            ], 403);
        }

        $assignments = TaskAssignment::where('volunteer_id', $user->volunteer->id)
            ->where('invitation_status', 'invited')
            ->with(['task.workstream', 'task.challenge'])
            ->latest()
            ->get();

        return response()->json([
            'assignments' => TaskAssignmentResource::collection($assignments),
        ]);
    }

    /**
     * Accept a task assignment.
     */
    public function accept(TaskAssignment $assignment, Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer || $assignment->volunteer_id !== $user->volunteer->id) {
            return response()->json([
                'message' => 'Unauthorized to accept this assignment',
            ], 403);
        }

        if ($assignment->invitation_status !== 'invited') {
            return response()->json([
                'message' => 'Assignment is not in pending status',
            ], 422);
        }

        // RULE: Volunteer can only accept ONE task at a time
        // Check if volunteer already has an active task
        $activeAssignment = TaskAssignment::where('volunteer_id', $user->volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'submitted'])
            ->first();

        if ($activeAssignment) {
            $errorMessage = 'You can only work on one task at a time. Please complete your current task before accepting a new one.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'active_task' => $activeAssignment->task->title,
                    'active_task_status' => $activeAssignment->invitation_status,
                ], 422);
            }

            return back()->with('error', $errorMessage . ' Current task: "' . $activeAssignment->task->title . '"');
        }

        // Check if NDA is signed for this challenge
        $challenge = $assignment->task->challenge;
        if ($challenge && $challenge->requires_nda) {
            if (!ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'You must sign the NDA for this challenge before accepting the task',
                        'nda_required' => true,
                        'challenge_id' => $challenge->id,
                    ], 403);
                }
                return redirect()->route('nda.challenge', $challenge)
                    ->with('warning', 'Please sign the challenge NDA before accepting this task.');
            }

            // Update task assignment NDA status
            $assignment->update([
                'nda_signed' => true,
                'nda_signed_at' => ChallengeNdaSigning::where('user_id', $user->id)
                    ->where('challenge_id', $challenge->id)
                    ->where('is_valid', true)
                    ->value('signed_at'),
            ]);
        }

        // Check volunteer availability
        $totalHours = TaskAssignment::where('volunteer_id', $user->volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress'])
            ->get()
            ->sum(function ($a) {
                return $a->task->estimated_hours ?? 0;
            });

        $newTaskHours = $assignment->task->estimated_hours ?? 0;
        $weeklyAvailability = $user->volunteer->availability_hours_per_week;

        if (($totalHours + $newTaskHours) > ($weeklyAvailability * 4)) { // Rough 4-week check
            return response()->json([
                'message' => 'Accepting this task would exceed your availability',
                'current_hours' => $totalHours,
                'new_task_hours' => $newTaskHours,
                'weekly_availability' => $weeklyAvailability,
            ], 422);
        }

        // Accept the assignment
        $assignment->update([
            'invitation_status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Update task status
        if ($assignment->task->status === 'pending' || $assignment->task->status === 'matching') {
            $assignment->task->update(['status' => 'assigned']);
        }

        // Notify company
        $this->notificationService->notifyTaskAccepted($assignment);

        // Check if this is a web request (has Accept: text/html)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task assignment accepted successfully',
                'assignment' => new TaskAssignmentResource($assignment->fresh(['task', 'volunteer'])),
            ]);
        }

        // Redirect to task page for web requests
        return redirect()->route('tasks.show', $assignment->task->id)
            ->with('success', 'Task invitation accepted! You can now start working on this task.');
    }

    /**
     * Reject a task assignment.
     */
    public function reject(TaskAssignment $assignment, Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer || $assignment->volunteer_id !== $user->volunteer->id) {
            return response()->json([
                'message' => 'Unauthorized to reject this assignment',
            ], 403);
        }

        if ($assignment->invitation_status !== 'invited') {
            return response()->json([
                'message' => 'Assignment is not in pending status',
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Reject the assignment
        $assignment->update([
            'invitation_status' => 'declined',
            'responded_at' => now(),
            'match_reasoning' => array_merge(
                json_decode($assignment->match_reasoning, true) ?? [],
                ['rejection_reason' => $validated['reason'] ?? 'No reason provided']
            ),
        ]);

        // Find and invite another volunteer for this task
        $task = $assignment->task;
        \App\Jobs\MatchVolunteersToTask::dispatch($task);

        // Check if this is a web request
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task assignment rejected',
            ]);
        }

        // Redirect to assignments page for web requests
        return redirect()->route('assignments.my')
            ->with('success', 'Task invitation declined. The task will be offered to other volunteers.');
    }

    /**
     * Start working on a task.
     */
    public function start(TaskAssignment $assignment, Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer || $assignment->volunteer_id !== $user->volunteer->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized to start this assignment',
                ], 403);
            }
            return back()->with('error', 'Unauthorized to start this assignment');
        }

        if ($assignment->invitation_status !== 'accepted') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Assignment must be accepted before starting',
                ], 422);
            }
            return back()->with('error', 'Assignment must be accepted before starting');
        }

        $assignment->update([
            'invitation_status' => 'in_progress',
            'started_at' => now(),
        ]);

        $assignment->task->update(['status' => 'in_progress']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task started successfully',
                'assignment' => new TaskAssignmentResource($assignment->fresh(['task'])),
            ]);
        }

        return redirect()->route('tasks.show', $assignment->task->id)
            ->with('success', 'You have started working on this task. Good luck!');
    }

    /**
     * Complete a task assignment.
     */
    public function complete(TaskAssignment $assignment, Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer || $assignment->volunteer_id !== $user->volunteer->id) {
            return response()->json([
                'message' => 'Unauthorized to complete this assignment',
            ], 403);
        }

        if ($assignment->invitation_status !== 'in_progress') {
            return response()->json([
                'message' => 'Assignment must be in progress to complete',
            ], 422);
        }

        $validated = $request->validate([
            'actual_hours' => 'required|numeric|min:0|max:1000',
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $assignment->update([
            'invitation_status' => 'completed',
            'completed_at' => now(),
            'actual_hours' => $validated['actual_hours'],
        ]);

        // Check if all assignments for this task are completed
        $pendingAssignments = $assignment->task->assignments()
            ->whereIn('invitation_status', ['pending', 'accepted', 'in_progress'])
            ->count();

        if ($pendingAssignments === 0) {
            $assignment->task->update(['status' => 'completed']);
        }

        // Notify company
        $this->notificationService->notifyTaskCompleted($assignment);

        return response()->json([
            'message' => 'Task completed successfully',
            'assignment' => new TaskAssignmentResource($assignment->fresh(['task'])),
        ]);
    }

    /**
     * Submit a solution for a task assignment.
     */
    public function submitSolution(TaskAssignment $assignment, Request $request)
    {
        $user = $request->user();

        // Authorization check
        if (!$user->volunteer || $assignment->volunteer_id !== $user->volunteer->id) {
            return back()->with('error', 'Unauthorized to submit solution for this assignment');
        }

        // Status check - assignment must be accepted or in_progress
        if (!in_array($assignment->invitation_status, ['accepted', 'in_progress'])) {
            return back()->with('error', 'Assignment must be accepted or in progress to submit a solution');
        }

        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|min:10|max:5000',
            'deliverable_url' => 'nullable|url|max:500',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,zip,png,jpg,jpeg',
            'hours_worked' => 'required|numeric|min:0.5|max:1000',
        ]);

        // Handle file uploads
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('submissions', 'public');
                $attachmentPaths[] = $path;
            }
        }

        // Create the work submission
        $submission = WorkSubmission::create([
            'task_assignment_id' => $assignment->id,
            'task_id' => $assignment->task_id,
            'volunteer_id' => $assignment->volunteer_id,
            'description' => $validated['description'],
            'deliverable_url' => $validated['deliverable_url'] ?? null,
            'attachments' => $attachmentPaths,
            'hours_worked' => $validated['hours_worked'],
            'status' => 'submitted',
            'ai_analysis_status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Update assignment status to submitted
        $assignment->update([
            'invitation_status' => 'submitted',
            'actual_hours' => $validated['hours_worked'],
        ]);

        // Dispatch AI analysis job
        AnalyzeSolutionQuality::dispatch($submission);

        // Notify company about the submission
        $this->notificationService->notifyTaskCompleted($assignment);

        return back()->with('success', 'Solution submitted successfully! AI is analyzing your solution...');
    }
}
