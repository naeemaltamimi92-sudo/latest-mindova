<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeAttachment;
use App\Jobs\AnalyzeChallengeBrief;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VolunteerChallengeController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display volunteer's submitted challenges.
     */
    public function index()
    {
        $volunteer = auth()->user()->volunteer;

        if (!$volunteer) {
            return redirect()->route('complete-profile')
                ->with('error', 'Please complete your volunteer profile first.');
        }

        $challenges = Challenge::where('volunteer_id', $volunteer->id)
            ->where('source_type', 'volunteer')
            ->with(['comments', 'attachments', 'challengeAnalyses'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('volunteer.challenges.index', compact('challenges'));
    }

    /**
     * Show a specific volunteer challenge.
     */
    public function show(Challenge $challenge)
    {
        $volunteer = auth()->user()->volunteer;

        if (!$volunteer) {
            return redirect()->route('complete-profile')
                ->with('error', 'Please complete your volunteer profile first.');
        }

        // Ensure the volunteer owns this challenge
        if ($challenge->volunteer_id !== $volunteer->id) {
            return redirect()->route('volunteer.challenges.index')
                ->with('error', 'You do not have access to this challenge.');
        }

        $challenge->load([
            'comments.user',
            'comments' => function ($query) {
                $query->orderBy('ai_score', 'desc')->orderBy('created_at', 'desc');
            },
            'attachments',
            'workstreams.tasks.assignments.volunteer.user',
            'challengeAnalyses',
        ]);

        return view('volunteer.challenges.show', compact('challenge'));
    }

    /**
     * Store a new volunteer challenge.
     */
    public function store(Request $request)
    {
        $volunteer = auth()->user()->volunteer;

        if (!$volunteer) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete your volunteer profile first.'
            ], 422);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        // Create the challenge
        $challenge = Challenge::create([
            'volunteer_id' => $volunteer->id,
            'source_type' => 'volunteer',
            'title' => $validated['title'],
            'original_description' => $validated['description'],
            'field' => $volunteer->field, // Use volunteer's field
            'status' => 'submitted',
            'ai_analysis_status' => 'pending',
        ]);

        // Handle attachments
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

        // Dispatch AI analysis job
        AnalyzeChallengeBrief::dispatch($challenge);

        // Notify admins
        $this->notificationService->notifyAdmins(
            'volunteer_challenge',
            'New Volunteer Challenge',
            "Volunteer {$volunteer->user->name} submitted a new challenge: {$challenge->title}",
            route('admin.challenges.show', $challenge)
        );

        return response()->json([
            'success' => true,
            'message' => 'Challenge submitted successfully! AI is analyzing it now...',
            'challenge_id' => $challenge->id,
        ]);
    }

    /**
     * Get challenge status (for AJAX polling).
     */
    public function status(Challenge $challenge)
    {
        $volunteer = auth()->user()->volunteer;

        if (!$volunteer) {
            return response()->json(['error' => 'No volunteer profile'], 422);
        }

        if ($challenge->volunteer_id !== $volunteer->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => $challenge->status,
            'ai_analysis_status' => $challenge->ai_analysis_status,
            'score' => $challenge->score,
            'challenge_type' => $challenge->challenge_type,
        ]);
    }

    /**
     * Update a volunteer challenge.
     */
    public function update(Request $request, Challenge $challenge)
    {
        $volunteer = auth()->user()->volunteer;

        if (!$volunteer) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete your volunteer profile first.'
            ], 422);
        }

        // Ensure the volunteer owns this challenge
        if ($challenge->volunteer_id !== $volunteer->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this challenge.'
            ], 403);
        }

        // Only allow editing if challenge is not completed/delivered and has no active tasks
        $hasActiveTasks = $challenge->tasks()->whereIn('status', ['in_progress', 'completed'])->exists();
        if (in_array($challenge->status, ['completed', 'delivered']) || $hasActiveTasks) {
            return response()->json([
                'success' => false,
                'message' => 'This challenge can no longer be edited because it has active or completed tasks.'
            ], 422);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
            'remove_attachments' => 'nullable|string',
        ]);

        // Store the previous score for notification purposes
        $previousScore = $challenge->score;

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

        // Notify assigned volunteers if this was a score 3-10 challenge (team execution)
        if ($previousScore && $previousScore >= 3) {
            $this->notifyAssignedVolunteers($challenge);
        }

        // Update the challenge
        $challenge->update([
            'title' => $validated['title'],
            'original_description' => $validated['description'],
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

        return response()->json([
            'success' => true,
            'message' => 'Challenge updated and resubmitted for analysis.',
        ]);
    }

    /**
     * Notify all volunteers assigned to tasks in this challenge about changes.
     */
    protected function notifyAssignedVolunteers(Challenge $challenge): void
    {
        // Get all unique volunteers assigned to tasks in this challenge
        $assignments = $challenge->tasks()
            ->with('assignments.volunteer.user')
            ->get()
            ->pluck('assignments')
            ->flatten()
            ->filter(function ($assignment) {
                return $assignment && $assignment->volunteer && $assignment->volunteer->user;
            });

        $notifiedVolunteerIds = [];

        foreach ($assignments as $assignment) {
            $volunteerId = $assignment->volunteer_id;

            // Avoid duplicate notifications
            if (in_array($volunteerId, $notifiedVolunteerIds)) {
                continue;
            }

            $this->notificationService->send(
                user: $assignment->volunteer->user,
                type: 'challenge_updated',
                title: 'Challenge Updated',
                message: "The challenge \"{$challenge->title}\" has been updated by the owner. Your assigned task \"{$assignment->task->title}\" may have new requirements. Please review the changes.",
                actionUrl: route('volunteer.challenges.show', $challenge)
            );

            $notifiedVolunteerIds[] = $volunteerId;
        }
    }

    /**
     * Delete a volunteer challenge.
     */
    public function destroy(Challenge $challenge)
    {
        $volunteer = auth()->user()->volunteer;

        if (!$volunteer) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete your volunteer profile first.'
            ], 422);
        }

        // Ensure the volunteer owns this challenge
        if ($challenge->volunteer_id !== $volunteer->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this challenge.'
            ], 403);
        }

        // Only allow deletion if challenge is not completed/delivered and has no active tasks
        $hasActiveTasks = $challenge->tasks()->whereIn('status', ['in_progress', 'completed'])->exists();
        if (in_array($challenge->status, ['completed', 'delivered']) || $hasActiveTasks) {
            return response()->json([
                'success' => false,
                'message' => 'This challenge can no longer be deleted because it has active or completed tasks.'
            ], 422);
        }

        // Delete attachments from storage
        foreach ($challenge->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete related records
        $challenge->attachments()->delete();
        $challenge->challengeAnalyses()->delete();
        $challenge->comments()->delete();

        // Delete the challenge
        $challenge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Challenge deleted successfully.',
        ]);
    }
}
