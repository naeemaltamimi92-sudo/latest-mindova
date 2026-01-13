<?php

namespace App\Http\Controllers\Idea;

use App\Http\Controllers\Controller;
use App\Http\Resources\IdeaResource;
use App\Jobs\ScoreIdea;
use App\Models\Challenge;
use App\Models\Idea;
use App\Models\IdeaVote;
use Illuminate\Http\Request;

class IdeaController extends Controller
{
    /**
     * Get all ideas for a challenge.
     */
    public function index(Request $request, Challenge $challenge)
    {
        if ($challenge->challenge_type !== 'community_discussion') {
            return response()->json([
                'message' => 'This challenge does not support community ideas',
            ], 422);
        }

        $query = $challenge->ideas()->with(['volunteer.user']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'final_score');
        if (in_array($sortBy, ['final_score', 'ai_score', 'community_votes', 'created_at'])) {
            $query->orderBy($sortBy, 'desc');
        }

        $ideas = $query->paginate(20);

        return IdeaResource::collection($ideas);
    }

    /**
     * Submit a new idea.
     */
    public function store(Request $request, Challenge $challenge)
    {
        if ($challenge->challenge_type !== 'community_discussion') {
            return response()->json([
                'message' => 'This challenge does not support community ideas',
            ], 422);
        }

        if ($challenge->status !== 'active') {
            return response()->json([
                'message' => 'Challenge is not active for idea submission',
            ], 422);
        }

        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Only volunteers can submit ideas',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:2000',
        ]);

        // Create idea (combine title and description into content field)
        $idea = Idea::create([
            'challenge_id' => $challenge->id,
            'volunteer_id' => $user->volunteer->id,
            'content' => "Title: {$validated['title']}\n\nDescription:\n{$validated['description']}",
            'status' => 'pending_review',
        ]);

        // Dispatch AI scoring job
        ScoreIdea::dispatch($idea);

        return response()->json([
            'message' => 'Idea submitted successfully. AI evaluation in progress.',
            'idea' => new IdeaResource($idea),
        ], 201);
    }

    /**
     * Get a specific idea.
     */
    public function show(Idea $idea)
    {
        $idea->load(['volunteer.user', 'challenge']);

        return response()->json([
            'idea' => new IdeaResource($idea),
        ]);
    }

    /**
     * Vote on an idea.
     */
    public function vote(Request $request, Idea $idea)
    {
        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Only volunteers can vote on ideas',
            ], 403);
        }

        if ($idea->volunteer_id === $user->volunteer->id) {
            return response()->json([
                'message' => 'Cannot vote on your own idea',
            ], 422);
        }

        if ($idea->status !== 'scored') {
            return response()->json([
                'message' => 'Can only vote on scored ideas',
            ], 422);
        }

        $validated = $request->validate([
            'vote' => 'required|integer|min:-1|max:1', // -1 (downvote), 0 (remove), 1 (upvote)
        ]);

        // Check for existing vote
        $existingVote = IdeaVote::where('idea_id', $idea->id)
            ->where('volunteer_id', $user->volunteer->id)
            ->first();

        if ($validated['vote'] === 0) {
            // Remove vote
            if ($existingVote) {
                $idea->decrement('community_votes', $existingVote->vote_value);
                $existingVote->delete();
            }
        } else {
            if ($existingVote) {
                // Update existing vote
                $oldVote = $existingVote->vote_value;
                $existingVote->update(['vote_value' => $validated['vote']]);

                // Adjust community votes
                $idea->increment('community_votes', $validated['vote'] - $oldVote);
            } else {
                // Create new vote
                IdeaVote::create([
                    'idea_id' => $idea->id,
                    'volunteer_id' => $user->volunteer->id,
                    'vote_value' => $validated['vote'],
                ]);

                $idea->increment('community_votes', $validated['vote']);
            }
        }

        // Recalculate final score
        $this->updateFinalScore($idea);

        return response()->json([
            'message' => 'Vote recorded successfully',
            'idea' => new IdeaResource($idea->fresh()),
        ]);
    }

    /**
     * Get volunteer's own ideas.
     */
    public function myIdeas(Request $request)
    {
        $user = $request->user();

        if (!$user->volunteer) {
            return response()->json([
                'message' => 'Only volunteers can view their ideas',
            ], 403);
        }

        $ideas = Idea::where('volunteer_id', $user->volunteer->id)
            ->with(['challenge'])
            ->latest()
            ->paginate(20);

        return IdeaResource::collection($ideas);
    }

    /**
     * Update final score combining AI and community votes.
     */
    protected function updateFinalScore(Idea $idea): void
    {
        $aiWeight = 0.4;
        $communityWeight = 0.6;

        $normalizedCommunityScore = max(0, min(100, 50 + ($idea->community_votes * 5)));
        $finalScore = ($idea->ai_score * $aiWeight) + ($normalizedCommunityScore * $communityWeight);

        $idea->update(['final_score' => $finalScore]);
    }
}
