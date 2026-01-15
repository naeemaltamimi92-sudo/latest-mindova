<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeComment;
use App\Models\CommentVote;
use App\Models\Idea;
use App\Models\IdeaVote;
use App\Jobs\AnalyzeCommentQuality;
use App\Jobs\ScoreIdea;
use App\Models\ReputationHistory;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommunityController extends Controller
{
    /**
     * Display community challenges (score 1-2).
     */
    public function index(Request $request)
    {
        // Get filter parameter, default to 'active'
        $filter = $request->get('filter', 'active');

        // Validate filter value
        $validFilters = ['active', 'completed', 'all'];
        if (!in_array($filter, $validFilters)) {
            $filter = 'active';
        }

        $query = Challenge::where('challenge_type', 'community_discussion')
            ->with(['company', 'volunteer.user', 'ideas'])
            ->withCount('ideas');

        // Apply status filter based on selection
        switch ($filter) {
            case 'active':
                $query->where('status', 'active');
                break;
            case 'completed':
                // Completed includes both 'completed' and 'closed' statuses
                $query->whereIn('status', ['completed', 'closed']);
                break;
            case 'all':
                // Show active, completed, and closed (but not archived/rejected)
                $query->whereIn('status', ['active', 'completed', 'closed']);
                break;
        }

        // Get user's field if they're a volunteer
        $userField = null;
        if (auth()->check()) {
            if (auth()->user()->isVolunteer()) {
                $userField = auth()->user()->volunteer->field ?? null;

                // STRICT: Volunteers can ONLY see challenges in their field (case-insensitive)
                if ($userField) {
                    $query->whereRaw('LOWER(field) = ?', [strtolower($userField)]);
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

        // Preserve filter in pagination links
        $challenges->appends(['filter' => $filter]);

        return view('community.index', compact('challenges', 'userField', 'filter'));
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

                if ($volunteerField && $challenge->field && strtolower($volunteerField) !== strtolower($challenge->field)) {
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

        // Determine if current user can see spam (admins or challenge owner)
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $isOwner = auth()->check() && auth()->user()->isCompany() && auth()->user()->company?->id === $challenge->company_id;
        $canSeeSpam = $isAdmin || $isOwner;

        $challenge->load([
            'company',
            'comments.user',
            'comments.votes',
            'comments' => function ($query) {
                $query->orderBy('ai_score', 'desc')->orderBy('created_at', 'desc');
            },
            'ideas' => function ($query) use ($canSeeSpam) {
                // Filter out spam ideas unless user is admin or challenge owner
                if (!$canSeeSpam) {
                    $query->where('is_spam', false);
                }
                // Sort by net votes (upvotes - downvotes), then by created_at
                $query->orderByRaw('(COALESCE(community_votes_up, 0) - COALESCE(community_votes_down, 0)) DESC')
                      ->orderBy('created_at', 'desc');
                $query->with('volunteer.user');
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

        return view('community.show', compact('challenge', 'highQualityIdeas', 'totalIdeas', 'userVotes', 'canSeeSpam'));
    }

    /**
     * Store a new idea on a community challenge.
     */
    public function storeComment(Request $request, Challenge $challenge)
    {
        // Check if challenge is closed
        if ($challenge->isClosed()) {
            return redirect()->back()->with('error', __('This challenge is closed and no longer accepting ideas.'));
        }

        // Ensure this is a community discussion challenge
        if ($challenge->challenge_type !== 'community_discussion') {
            return redirect()->back()->with('error', 'Ideas are not allowed on this challenge.');
        }

        // Only volunteers can submit ideas
        if (!auth()->user()->isVolunteer()) {
            return redirect()->back()->with('error', 'Only volunteers can submit ideas.');
        }

        $volunteer = auth()->user()->volunteer;

        // Check field access permissions
        $volunteerField = $volunteer->field ?? null;
        if ($volunteerField && $challenge->field && strtolower($volunteerField) !== strtolower($challenge->field)) {
            return redirect()->back()
                ->with('error', 'You can only submit ideas on challenges in your field: ' . $volunteerField);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
        ]);

        // Create idea
        $idea = Idea::create([
            'challenge_id' => $challenge->id,
            'volunteer_id' => $volunteer->id,
            'content' => $validated['content'],
            'status' => 'pending_review',
        ]);

        // Dispatch AI scoring job
        ScoreIdea::dispatch($idea);

        return redirect()->route('community.challenge', $challenge)
            ->with('success', 'Idea submitted successfully! AI is analyzing its quality...');
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

    /**
     * Mark an idea as the correct answer for a challenge.
     */
    public function markCorrectAnswer(Request $request, Challenge $challenge, Idea $idea)
    {
        // Ensure this is a community discussion challenge
        if ($challenge->challenge_type !== 'community_discussion') {
            return redirect()->back()
                ->with('error', __('Only community discussion challenges can have correct answers.'));
        }

        // Verify the idea belongs to this challenge
        if ($idea->challenge_id !== $challenge->id) {
            return redirect()->back()
                ->with('error', __('This idea does not belong to this challenge.'));
        }

        // Check authorization - only owner can mark correct answer
        if (!$challenge->isOwnedBy(auth()->user())) {
            return redirect()->back()
                ->with('error', __('Only the challenge owner can mark the correct answer.'));
        }

        // Check if challenge is still active
        if ($challenge->status !== 'active') {
            return redirect()->back()
                ->with('error', __('This challenge is no longer active.'));
        }

        // Check if already has a correct answer
        if ($challenge->hasCorrectAnswer()) {
            return redirect()->back()
                ->with('error', __('This challenge already has a correct answer marked.'));
        }

        // Cannot mark own idea as correct (for volunteer-submitted challenges)
        if ($challenge->isVolunteerSubmitted() &&
            $idea->volunteer_id === $challenge->volunteer_id) {
            return redirect()->back()
                ->with('error', __('You cannot mark your own idea as the correct answer.'));
        }

        // Perform the marking operation
        DB::transaction(function () use ($challenge, $idea) {
            // Update the idea
            $idea->update([
                'is_correct_answer' => true,
                'marked_correct_at' => now(),
            ]);

            // Update the challenge
            $challenge->update([
                'correct_idea_id' => $idea->id,
                'status' => 'completed',
                'closed_at' => now(),
            ]);

            // Award reputation points to the idea owner
            $this->awardCorrectAnswerPoints($idea);
        });

        return redirect()->route('community.challenge', $challenge)
            ->with('success', __('Correct answer marked successfully! The challenge is now completed.'));
    }

    /**
     * Award reputation points for having an idea marked as correct.
     */
    protected function awardCorrectAnswerPoints(Idea $idea): void
    {
        $volunteer = $idea->volunteer;

        if (!$volunteer) {
            return;
        }

        // Get points from config (default 50)
        $points = config('gamification.correct_answer_points', 50);

        // Update volunteer's reputation score
        $oldScore = $volunteer->reputation_score ?? 50;
        $newScore = $oldScore + $points;

        $volunteer->update(['reputation_score' => $newScore]);

        // Record in reputation history
        ReputationHistory::create([
            'volunteer_id' => $volunteer->id,
            'change_amount' => $points,
            'new_total' => $newScore,
            'reason' => __('Idea marked as correct answer'),
            'related_type' => Idea::class,
            'related_id' => $idea->id,
            'created_at' => now(),
        ]);

        Log::info('Awarded correct answer points', [
            'volunteer_id' => $volunteer->id,
            'idea_id' => $idea->id,
            'points' => $points,
            'new_total' => $newScore,
        ]);

        // Send notification to the contributor
        if ($volunteer->user) {
            $notificationService = app(NotificationService::class);
            $notificationService->send(
                user: $volunteer->user,
                type: 'idea_marked_correct',
                title: __('Your Idea Was Marked as Correct!'),
                message: __('Congratulations! Your idea for ":challenge" was marked as the correct answer. You earned :points reputation points!', [
                    'challenge' => $idea->challenge->title,
                    'points' => $points,
                ]),
                actionUrl: route('community.challenge', $idea->challenge_id)
            );
        }
    }
}
