<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeComment;
use App\Models\CommentVote;
use App\Models\Idea;
use App\Models\IdeaVote;
use App\Jobs\AnalyzeCommentQuality;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * Display community challenges (score 1-2).
     */
    public function index(Request $request)
    {
        $query = Challenge::where('challenge_type', 'community_discussion')
            ->where('score', '>=', 1)
            ->where('score', '<=', 2)
            ->with(['company', 'volunteer.user', 'comments'])
            ->withCount('comments');

        // Get user's field if they're a volunteer
        $userField = null;
        if (auth()->check()) {
            if (auth()->user()->isVolunteer()) {
                $userField = auth()->user()->volunteer->field ?? null;

                // STRICT: Volunteers can ONLY see challenges in their field
                if ($userField) {
                    $query->where('field', $userField);
                } else {
                    // If volunteer has no field set, show nothing
                    $query->whereRaw('1 = 0'); // Returns empty result
                }
            } elseif (auth()->user()->isCompany()) {
                // STRICT: Companies can ONLY see their own challenges
                $company = auth()->user()->company;
                if ($company) {
                    $query->where('company_id', $company->id);
                } else {
                    // If company profile not found, show nothing
                    $query->whereRaw('1 = 0');
                }
            }
        } else {
            // Unauthenticated users see nothing
            $query->whereRaw('1 = 0');
        }

        $challenges = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('community.index', compact('challenges', 'userField'));
    }

    /**
     * Show a specific community challenge with comments.
     */
    public function show(Challenge $challenge)
    {
        // Ensure this is a community discussion challenge
        if ($challenge->challenge_type !== 'community_discussion') {
            return redirect()->route('challenges.show', $challenge)
                ->with('info', 'This challenge is not open for community discussion.');
        }

        // Check access permissions
        if (auth()->check()) {
            if (auth()->user()->isVolunteer()) {
                // Volunteers can only see challenges in their field
                $volunteerField = auth()->user()->volunteer->field ?? null;

                if ($volunteerField && $challenge->field && $volunteerField !== $challenge->field) {
                    return redirect()->route('community.index')
                        ->with('error', 'This challenge is not in your field of expertise. You can only view and comment on challenges in your field: ' . $volunteerField);
                }
            } elseif (auth()->user()->isCompany()) {
                // Companies can only see their own challenges
                $company = auth()->user()->company;
                if (!$company || $challenge->company_id !== $company->id) {
                    return redirect()->route('community.index')
                        ->with('error', 'You can only view your own challenges.');
                }
            }
        } else {
            return redirect()->route('login')
                ->with('error', 'Please sign in to view community challenges.');
        }

        $challenge->load([
            'company',
            'comments.user',
            'comments.votes',
            'comments' => function ($query) {
                $query->orderBy('ai_score', 'desc')->orderBy('created_at', 'desc');
            },
            'ideas.volunteer.user',
            'ideas' => function ($query) {
                $query->orderBy('ai_quality_score', 'desc')->orderBy('created_at', 'desc');
            }
        ]);

        // Get high-quality ideas (score >= 7)
        $highQualityIdeas = $challenge->ideas->where('ai_quality_score', '>=', 7);

        // Get total ideas count
        $totalIdeas = $challenge->ideas->count();

        // Get user's votes on ideas (for highlighting active votes)
        $userVotes = collect();
        if (auth()->check() && auth()->user()->isVolunteer()) {
            $volunteer = auth()->user()->volunteer;
            $ideaIds = $challenge->ideas->pluck('id');
            $userVotes = IdeaVote::where('volunteer_id', $volunteer->id)
                ->whereIn('idea_id', $ideaIds)
                ->get()
                ->keyBy('idea_id');
        }

        return view('community.show', compact('challenge', 'highQualityIdeas', 'totalIdeas', 'userVotes'));
    }

    /**
     * Store a new comment on a community challenge.
     */
    public function storeComment(Request $request, Challenge $challenge)
    {
        // Ensure this is a community discussion challenge
        if ($challenge->challenge_type !== 'community_discussion') {
            return redirect()->back()->with('error', 'Comments are not allowed on this challenge.');
        }

        // Check access permissions for commenting
        if (auth()->user()->isVolunteer()) {
            $volunteerField = auth()->user()->volunteer->field ?? null;

            if ($volunteerField && $challenge->field && $volunteerField !== $challenge->field) {
                return redirect()->back()
                    ->with('error', 'You can only comment on challenges in your field: ' . $volunteerField);
            }
        } elseif (auth()->user()->isCompany()) {
            // Companies can only comment on their own challenges
            $company = auth()->user()->company;
            if (!$company || $challenge->company_id !== $company->id) {
                return redirect()->back()
                    ->with('error', 'You can only comment on your own challenges.');
            }
        }

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
        ]);

        // Create comment
        $comment = ChallengeComment::create([
            'challenge_id' => $challenge->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'ai_score_status' => 'pending',
        ]);

        // Dispatch AI scoring job
        AnalyzeCommentQuality::dispatch($comment);

        return redirect()->route('community.challenge', $challenge)
            ->with('success', 'Comment posted successfully! AI is analyzing its quality...');
    }

    /**
     * Vote on a comment (upvote or downvote).
     */
    public function voteComment(Request $request, ChallengeComment $comment)
    {
        $validated = $request->validate([
            'vote_type' => 'required|in:upvote,downvote',
        ]);

        $userId = auth()->id();
        $voteType = $validated['vote_type'];

        // Check if user already voted on this comment
        $existingVote = CommentVote::where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $voteType) {
                // Remove vote if clicking same button again
                $existingVote->delete();
                $message = 'Vote removed.';
            } else {
                // Change vote type
                $existingVote->update(['vote_type' => $voteType]);
                $message = ucfirst($voteType) . ' recorded.';
            }
        } else {
            // Create new vote
            CommentVote::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'vote_type' => $voteType,
            ]);
            $message = ucfirst($voteType) . ' recorded.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Vote on an idea (upvote or downvote).
     */
    public function voteIdea(Request $request, Idea $idea)
    {
        // Only volunteers can vote
        if (!auth()->user()->isVolunteer()) {
            return redirect()->back()->with('error', 'Only volunteers can vote on ideas.');
        }

        $volunteer = auth()->user()->volunteer;

        // Cannot vote on own idea
        if ($idea->volunteer_id === $volunteer->id) {
            return redirect()->back()->with('error', 'You cannot vote on your own idea.');
        }

        $validated = $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);

        $voteType = $validated['vote_type'];

        // Check if user already voted on this idea
        $existingVote = IdeaVote::where('idea_id', $idea->id)
            ->where('volunteer_id', $volunteer->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $voteType) {
                // Remove vote if clicking same button again
                if ($voteType === 'up') {
                    $idea->decrement('community_votes_up');
                } else {
                    $idea->decrement('community_votes_down');
                }
                $existingVote->delete();
                $message = 'Vote removed.';
            } else {
                // Change vote type
                if ($voteType === 'up') {
                    $idea->increment('community_votes_up');
                    $idea->decrement('community_votes_down');
                } else {
                    $idea->decrement('community_votes_up');
                    $idea->increment('community_votes_down');
                }
                $existingVote->update(['vote_type' => $voteType]);
                $message = ($voteType === 'up' ? 'Upvote' : 'Downvote') . ' recorded.';
            }
        } else {
            // Create new vote
            IdeaVote::create([
                'idea_id' => $idea->id,
                'volunteer_id' => $volunteer->id,
                'vote_type' => $voteType,
            ]);

            if ($voteType === 'up') {
                $idea->increment('community_votes_up');
            } else {
                $idea->increment('community_votes_down');
            }
            $message = ($voteType === 'up' ? 'Upvote' : 'Downvote') . ' recorded.';
        }

        return redirect()->back()->with('success', $message);
    }
}
