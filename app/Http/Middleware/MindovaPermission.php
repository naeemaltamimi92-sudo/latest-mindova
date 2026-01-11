<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MindovaTeamMember;

class MindovaPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $permissions  Comma-separated list of permissions (OR logic)
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $teamMember = $request->mindovaTeamMember ?? MindovaTeamMember::find(session('mindova_team_member_id'));

        if (!$teamMember) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('mindova.login');
        }

        // Parse permissions (OR logic - user needs at least one)
        $requiredPermissions = explode(',', $permissions);

        foreach ($requiredPermissions as $permission) {
            if ($teamMember->hasPermission(trim($permission))) {
                return $next($request);
            }
        }

        // No matching permission found
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => __('You do not have permission to perform this action.'),
            ], 403);
        }

        return redirect()->route('mindova.dashboard')
            ->with('error', __('You do not have permission to access this resource.'));
    }
}
