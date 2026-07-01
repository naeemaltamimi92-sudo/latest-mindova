<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\StoreFeedbackCommentRequest;
use App\Http\Requests\Feedback\StoreFeedbackItemRequest;
use App\Models\FeedbackComment;
use App\Models\FeedbackItem;
use App\Models\FeedbackVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Public board: list, filter, sort, paginate.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = FeedbackItem::query()
            ->with(['user:id,name'])
            ->withCount('comments')
            ->when($userId, fn ($q) => $q->with(['votes' => fn ($v) => $v->where('user_id', $userId)]));

        if ($request->filled('type') && in_array($request->type, FeedbackItem::TYPES, true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && in_array($request->status, FeedbackItem::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'newest');
        $query->when($sort === 'votes', fn ($q) => $q->orderByDesc('votes_count')->orderByDesc('created_at'))
              ->when($sort !== 'votes', fn ($q) => $q->orderByDesc('created_at'));

        $items = $query->paginate(15)->withQueryString();

        return view('feedback.index', [
            'items' => $items,
            'types' => FeedbackItem::TYPES,
            'statuses' => FeedbackItem::STATUSES,
            'currentType' => $request->get('type'),
            'currentStatus' => $request->get('status'),
            'currentSort' => $sort,
        ]);
    }

    public function create()
    {
        return view('feedback.create', ['types' => FeedbackItem::TYPES]);
    }

    public function store(StoreFeedbackItemRequest $request)
    {
        $item = FeedbackItem::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'status' => 'open',
            'votes_count' => 0,
        ]);

        return redirect()->route('feedback.show', $item)
            ->with('success', 'Thanks — your feedback has been posted to the board.');
    }

    public function show(FeedbackItem $feedbackItem)
    {
        $userId = auth()->id();

        $feedbackItem->load([
            'user:id,name',
            'duplicateOf:id,title',
            'comments.user:id,name',
        ]);

        if ($userId) {
            $feedbackItem->load(['votes' => fn ($v) => $v->where('user_id', $userId)]);
        }

        return view('feedback.show', ['item' => $feedbackItem]);
    }

    public function edit(FeedbackItem $feedbackItem)
    {
        $this->authorizeOwnerOrAdmin($feedbackItem);

        return view('feedback.edit', ['item' => $feedbackItem, 'types' => FeedbackItem::TYPES]);
    }

    public function update(StoreFeedbackItemRequest $request, FeedbackItem $feedbackItem)
    {
        $this->authorizeOwnerOrAdmin($feedbackItem);

        $feedbackItem->update($request->validated());

        return redirect()->route('feedback.show', $feedbackItem)->with('success', 'Feedback updated.');
    }

    public function destroy(FeedbackItem $feedbackItem)
    {
        $this->authorizeOwnerOrAdmin($feedbackItem);

        $feedbackItem->delete();

        return redirect()->route('feedback.index')->with('success', 'Feedback removed.');
    }

    /**
     * Toggle an upvote. Locked read-modify-write keeps votes_count
     * consistent even under concurrent requests; the DB-level unique
     * constraint on (user_id, feedback_item_id) is the second line of
     * defense against a double-click race creating two votes.
     */
    public function vote(FeedbackItem $feedbackItem)
    {
        $user = auth()->user();

        DB::transaction(function () use ($feedbackItem, $user) {
            $locked = FeedbackItem::lockForUpdate()->find($feedbackItem->id);

            $existingVote = FeedbackVote::where('feedback_item_id', $locked->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingVote) {
                $existingVote->delete();
                $locked->decrement('votes_count');
            } else {
                FeedbackVote::create([
                    'user_id' => $user->id,
                    'feedback_item_id' => $locked->id,
                    'created_at' => now(),
                ]);
                $locked->increment('votes_count');
            }
        });

        return redirect()->back()->with('success', 'Vote updated.');
    }

    public function storeComment(StoreFeedbackCommentRequest $request, FeedbackItem $feedbackItem)
    {
        FeedbackComment::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'feedback_item_id' => $feedbackItem->id,
        ]);

        return redirect()->route('feedback.show', $feedbackItem)->with('success', 'Comment posted.');
    }

    private function authorizeOwnerOrAdmin(FeedbackItem $feedbackItem): void
    {
        $user = auth()->user();

        abort_unless($user && ($user->id === $feedbackItem->user_id || $user->isAdmin()), 403);
    }
}
