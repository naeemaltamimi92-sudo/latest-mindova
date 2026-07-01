<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminFeedbackController extends Controller
{
    use \App\Http\Controllers\Admin\Concerns\FiltersAdminIndex;

    public function index(Request $request)
    {
        $query = FeedbackItem::query()->with(['user:id,name', 'duplicateOf:id,title']);

        $this->applySearch($query, $request->get('search'), ['title', 'description']);

        if ($request->filled('type') && in_array($request->type, FeedbackItem::TYPES, true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && in_array($request->status, FeedbackItem::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        $this->applySort($query, $request, ['votes_count', 'created_at'], 'votes_count');

        $items = $query->paginate(20)->withQueryString();

        return view('admin.feedback.index', [
            'items' => $items,
            'types' => FeedbackItem::TYPES,
            'statuses' => FeedbackItem::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, FeedbackItem $feedbackItem)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(FeedbackItem::STATUSES)],
        ]);

        $feedbackItem->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status updated to "' . str_replace('_', ' ', $validated['status']) . '".');
    }

    public function markDuplicate(Request $request, FeedbackItem $feedbackItem)
    {
        $validated = $request->validate([
            'duplicate_of_id' => 'nullable|exists:feedback_items,id|different:feedback_item',
        ]);

        $duplicateOfId = $validated['duplicate_of_id'] ?? null;

        if ($duplicateOfId == $feedbackItem->id) {
            return redirect()->back()->with('error', 'A feedback item cannot be marked as a duplicate of itself.');
        }

        $feedbackItem->update(['duplicate_of_id' => $duplicateOfId]);

        return redirect()->back()->with('success', $duplicateOfId ? 'Marked as duplicate.' : 'Duplicate link removed.');
    }
}
