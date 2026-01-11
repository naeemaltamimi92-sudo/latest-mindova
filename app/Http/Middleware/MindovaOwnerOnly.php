<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MindovaTeamMember;

class MindovaOwnerOnly
{
    /**
     * Handle an incoming request.
     * Only allows access to the Owner role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamMember = $request->mindovaTeamMember ?? MindovaTeamMember::find(session('mindova_team_member_id'));

        if (!$teamMember) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('mindova.login');
        }

        if (!$teamMember->isOwner()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => __('Only the Owner can perform this action.'),
                ], 403);
            }

            return redirect()->route('mindova.dashboard')
                ->with('error', __('Only the Owner can access this resource.'));
        }

        return $next($request);
    }
}
