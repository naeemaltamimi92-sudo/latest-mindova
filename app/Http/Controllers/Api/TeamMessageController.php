<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeamMessageController extends Controller
{
    /**
     * Get messages for a team.
     */
    public function index(Team $team)
    {
        // Verify user is a member of this team
        $user = auth()->user();
        $volunteer = $user->volunteer;

        if (!$volunteer) {
            return response()->json(['error' => 'Not a volunteer'], 403);
        }

        $isMember = $team->members()
            ->where('volunteer_id', $volunteer->id)
            ->exists();

        if (!$isMember) {
            return response()->json(['error' => 'Not a team member'], 403);
        }

        // Get messages with user information
        $messages = TeamMessage::where('team_id', $team->id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'time_ago' => $message->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Send a message to the team.
     */
    public function store(Request $request, Team $team)
    {
        // Verify user is a member of this team
        $user = auth()->user();
        $volunteer = $user->volunteer;

        if (!$volunteer) {
            return response()->json(['error' => 'Not a volunteer'], 403);
        }

        $isMember = $team->members()
            ->where('volunteer_id', $volunteer->id)
            ->exists();

        if (!$isMember) {
            return response()->json(['error' => 'Not a team member'], 403);
        }

        // Validate message
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Create message
        $message = TeamMessage::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        Log::info('Team message sent', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'user_name' => $user->name,
                'message' => $message->message,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time_ago' => $message->created_at->diffForHumans(),
            ],
        ]);
    }
}
