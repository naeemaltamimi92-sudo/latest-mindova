<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MobileChallengeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Challenge::with(['company'])
            ->where('status', 'active')
            ->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('challenge_type', $request->type);
        }

        $challenges = $query->paginate(15);

        return response()->json([
            'data'         => $challenges->map(fn($c) => $this->formatChallenge($c)),
            'current_page' => $challenges->currentPage(),
            'last_page'    => $challenges->lastPage(),
            'total'        => $challenges->total(),
        ]);
    }

    public function show(Request $request, Challenge $challenge): JsonResponse
    {
        $challenge->load(['company', 'tasks']);

        $ideas = Idea::where('challenge_id', $challenge->id)
            ->with('volunteer.user')
            ->withCount('votes')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($i) => [
                'id'          => $i->id,
                'content'     => $i->content,
                'votes_count' => $i->votes_count,
                'created_at'  => $i->created_at,
                'author'      => $i->volunteer ? $i->volunteer->user->name : 'Anonymous',
            ]);

        return response()->json([
            'challenge' => $this->formatChallenge($challenge, true),
            'ideas'     => $ideas,
        ]);
    }

    public function myChallenges(Request $request): JsonResponse
    {
        $user    = $request->user();
        $company = $user->company;

        if (! $company) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $challenges = Challenge::where('company_id', $company->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'data'         => $challenges->map(fn($c) => $this->formatChallenge($c)),
            'current_page' => $challenges->currentPage(),
            'last_page'    => $challenges->lastPage(),
            'total'        => $challenges->total(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user    = $request->user();
        $company = $user->company;

        if (! $company) {
            return response()->json(['message' => 'Company profile required.'], 422);
        }

        $validated = $request->validate([
            'title'                => 'required|string|max:255',
            'original_description' => 'required|string|min:50',
            'challenge_type'       => 'nullable|in:technical,business,creative,research',
        ]);

        $challenge = Challenge::create([
            'company_id'           => $company->id,
            'title'                => $validated['title'],
            'original_description' => $validated['original_description'],
            'challenge_type'       => $validated['challenge_type'] ?? 'technical',
            'status'               => 'submitted',
        ]);

        return response()->json([
            'challenge' => $this->formatChallenge($challenge),
            'message'   => 'Challenge submitted successfully. AI analysis will begin shortly.',
        ], 201);
    }

    public function community(Request $request): JsonResponse
    {
        $challenges = Challenge::with(['company'])
            ->whereIn('status', ['active', 'completed'])
            ->has('ideas')
            ->withCount('ideas')
            ->latest()
            ->paginate(15);

        return response()->json([
            'data'         => $challenges->map(fn($c) => $this->formatChallenge($c)),
            'current_page' => $challenges->currentPage(),
            'last_page'    => $challenges->lastPage(),
            'total'        => $challenges->total(),
        ]);
    }

    public function postIdea(Request $request, Challenge $challenge): JsonResponse
    {
        $user      = $request->user();
        $volunteer = $user->volunteer;

        if (! $volunteer) {
            return response()->json(['message' => 'Volunteer profile required.'], 422);
        }

        $request->validate(['content' => 'required|string|min:10|max:10000']);

        $idea = Idea::create([
            'challenge_id'  => $challenge->id,
            'volunteer_id'  => $volunteer->id,
            'content'       => $request->content,
        ]);

        return response()->json([
            'idea'    => ['id' => $idea->id, 'content' => $idea->content, 'created_at' => $idea->created_at],
            'message' => 'Idea posted successfully.',
        ], 201);
    }

    private function formatChallenge(Challenge $c, bool $full = false): array
    {
        $base = [
            'id'          => $c->id,
            'title'       => $c->title,
            'status'      => $c->status,
            'description' => str($c->refined_brief ?? $c->original_description)->limit(200)->toString(),
            'type'        => $c->challenge_type,
            'complexity'  => $c->complexity_level,
            'score'       => $c->score,
            'created_at'  => $c->created_at,
            'company'     => $c->company ? [
                'id'   => $c->company->id,
                'name' => $c->company->name,
                'logo' => $c->company->logo ? asset('storage/' . $c->company->logo) : null,
            ] : null,
        ];

        if ($full) {
            $base['full_description'] = $c->refined_brief ?? $c->original_description;
            $base['task_count']       = $c->tasks ? $c->tasks->count() : 0;
        }

        return $base;
    }
}
