<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MindovaTeamMember;
use App\Models\MindovaRole;
use App\Models\MindovaAuditLog;

class MindovaTeamAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in as a Mindova team member
        $teamMemberId = session('mindova_team_member_id');
        $teamMember = null;

        if ($teamMemberId) {
            $teamMember = MindovaTeamMember::with('role')->find($teamMemberId);
        }

        // If not logged in to Mindova admin, try auto-login from main site
        // But not if user just logged out (check for logout flag)
        if (!$teamMember && auth()->check() && !session('mindova_logout_flag')) {
            $user = auth()->user();

            // Check if user is admin
            if ($user->user_type === 'admin') {
                // Find or create team member for this admin
                $teamMember = MindovaTeamMember::where('user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();

                if ($teamMember) {
                    // Link user_id if not already linked
                    if (!$teamMember->user_id) {
                        $teamMember->update(['user_id' => $user->id]);
                    }
                } else {
                    // Auto-create as Owner if no owner exists, otherwise as Admin
                    $ownerExists = MindovaTeamMember::whereHas('role', fn($q) => $q->where('slug', 'owner'))->exists();

                    if (!$ownerExists) {
                        $role = MindovaRole::where('slug', 'owner')->first();
                    } else {
                        $role = MindovaRole::where('slug', 'admin')->first();
                    }

                    if ($role) {
                        $teamMember = MindovaTeamMember::create([
                            'user_id' => $user->id,
                            'role_id' => $role->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'password_changed' => true, // Already has password via main site
                            'is_active' => true,
                            'activated_at' => now(),
                        ]);
                    }
                }

                if ($teamMember && $teamMember->is_active) {
                    session(['mindova_team_member_id' => $teamMember->id]);
                    $teamMember->updateLastLogin();
                    MindovaAuditLog::logLogin($teamMember);
                    $teamMember->load('role');
                }
            }
        }

        if (!$teamMember || !$teamMember->is_active) {
            session()->forget('mindova_team_member_id');
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // If logged in but not an admin, show error
            if (auth()->check() && auth()->user()->user_type !== 'admin') {
                return redirect()->route('mindova.login')
                    ->with('error', __('Only admin users can access Mindova Organization.'));
            }

            return redirect()->route('mindova.login');
        }

        // Check if password change is required (skip for auto-created members)
        if (!$teamMember->password_changed && !$request->routeIs('mindova.password.change*')) {
            return redirect()->route('mindova.password.change');
        }

        // Share team member with all views
        view()->share('currentTeamMember', $teamMember);
        $request->merge(['mindovaTeamMember' => $teamMember]);

        return $next($request);
    }
}
