<?php

namespace App\Http\Controllers;

use App\Models\MindyMessage;
use App\Services\AI\MindyAssistantService;
use App\Services\MindyContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MindyController extends Controller
{
    private const HISTORY_LIMIT = 20;

    public function history(Request $request): JsonResponse
    {
        $messages = MindyMessage::where('user_id', $request->user()->id)
            ->orderBy('created_at')
            ->limit(self::HISTORY_LIMIT)
            ->get(['role', 'content', 'created_at']);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function chat(Request $request, MindyContextService $contextService, MindyAssistantService $assistant): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'current_page' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        $history = MindyMessage::where('user_id', $user->id)
            ->orderBy('created_at')
            ->limit(self::HISTORY_LIMIT)
            ->get();

        $context = $contextService->build($user, $validated['current_page'] ?? null);

        try {
            $reply = $assistant->reply($user, $context, $history, $validated['message']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "I'm having trouble reaching my knowledge right now. Please try again in a moment.",
            ], 503);
        }

        MindyMessage::create([
            'user_id' => $user->id,
            'role' => 'user',
            'content' => $validated['message'],
            'created_at' => now(),
        ]);

        MindyMessage::create([
            'user_id' => $user->id,
            'role' => 'assistant',
            'content' => $reply,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}
