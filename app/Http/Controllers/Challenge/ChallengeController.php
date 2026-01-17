<?php

namespace App\Http\Controllers\Challenge;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengeResource;
use App\Jobs\AnalyzeChallengeBrief;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    /**
     * Get all challenges.
     */
    public function index(Request $request)
    {
        $query = Challenge::with(['company', 'workstreams', 'ideas']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by challenge type
        if ($request->has('type')) {
            $query->where('challenge_type', $request->type);
        }

        // Filter by complexity
        if ($request->has('complexity')) {
            $query->where('complexity_level', $request->complexity);
        }

        $challenges = $query->latest()->paginate(15);

        return ChallengeResource::collection($challenges);
    }

    /**
     * Submit a new challenge.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:5000',
        ]);

        $user = $request->user();

        if (!$user->company) {
            return response()->json([
                'message' => 'Only companies can submit challenges',
            ], 403);
        }

        // Create challenge
        $challenge = Challenge::create([
            'company_id' => $user->company->id,
            'title' => $validated['title'],
            'original_description' => $validated['description'],
            'status' => 'submitted',
        ]);

        // Increment company's challenge count
        $user->company->increment('total_challenges_submitted');

        // Dispatch AI analysis job
        AnalyzeChallengeBrief::dispatch($challenge);

        return response()->json([
            'message' => 'Challenge submitted successfully. AI analysis has begun.',
            'challenge' => new ChallengeResource($challenge),
        ], 201);
    }

    /**
     * Get a specific challenge.
     */
    public function show(Challenge $challenge)
    {
        $challenge->load([
            'company',
            'challengeAnalyses',
            'workstreams.tasks',
            'tasks.assignments.volunteer',
            'ideas.volunteer',
        ]);

        return response()->json([
            'challenge' => new ChallengeResource($challenge),
        ]);
    }

    /**
     * Update a challenge (only by owning company).
     */
    public function update(Request $request, Challenge $challenge)
    {
        $user = $request->user();

        if (!$user->company || $challenge->company_id !== $user->company->id) {
            return response()->json([
                'message' => 'Unauthorized to update this challenge',
            ], 403);
        }

        // Only allow updates if challenge is in submitted or analyzing status
        if (!in_array($challenge->status, ['submitted', 'analyzing'])) {
            return response()->json([
                'message' => 'Cannot update challenge in current status',
            ], 422);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:100|max:5000',
        ]);

        if (isset($validated['description'])) {
            $validated['original_description'] = $validated['description'];
            unset($validated['description']);
        }

        $challenge->update($validated);

        return response()->json([
            'message' => 'Challenge updated successfully',
            'challenge' => new ChallengeResource($challenge->fresh()),
        ]);
    }

    /**
     * Archive a challenge.
     */
    public function archive(Challenge $challenge)
    {
        $user = request()->user();

        if (!$user->company || $challenge->company_id !== $user->company->id) {
            return response()->json([
                'message' => 'Unauthorized to archive this challenge',
            ], 403);
        }

        $challenge->update(['status' => 'archived']);

        return response()->json([
            'message' => 'Challenge archived successfully',
            'challenge' => new ChallengeResource($challenge),
        ]);
    }

    /**
     * Get challenges for the authenticated company.
     */
    public function myChallenges(Request $request)
    {
        $user = $request->user();

        if (!$user->company) {
            return response()->json([
                'message' => 'Only companies can view their challenges',
            ], 403);
        }

        $challenges = Challenge::where('company_id', $user->company->id)
            ->with(['workstreams', 'ideas'])
            ->latest()
            ->paginate(15);

        return ChallengeResource::collection($challenges);
    }
}
