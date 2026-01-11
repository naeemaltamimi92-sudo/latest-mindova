<?php

namespace App\Http\Controllers\Mindova;

use App\Http\Controllers\Controller;
use App\Models\MindovaTeamMember;
use App\Models\MindovaAuditLog;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index(Request $request)
    {
        $teamMember = $request->mindovaTeamMember;

        // Get statistics based on permissions
        $stats = [];

        if ($teamMember->hasPermission('users.view')) {
            $stats['total_users'] = User::count();
            $stats['volunteers'] = User::where('user_type', 'volunteer')->count();
        }

        if ($teamMember->hasPermission('companies.view')) {
            $stats['companies'] = Company::count();
        }

        // Team stats - always show team member count
        $stats['team_members'] = MindovaTeamMember::where('is_active', true)->count();

        // Active today - team members who logged in today
        $stats['active_today'] = MindovaTeamMember::where('is_active', true)
            ->whereDate('last_login_at', Carbon::today())
            ->count();

        // Get opportunities count if model exists
        if (class_exists(\App\Models\Opportunity::class)) {
            try {
                $stats['opportunities'] = \App\Models\Opportunity::count();
            } catch (\Exception $e) {
                // Ignore if table doesn't exist
            }
        }

        // Get recent audit logs based on permissions
        $recentLogs = [];
        if ($teamMember->hasPermission('audit.view')) {
            $recentLogs = MindovaAuditLog::getRecent(10);
        }

        // Get team members for the overview section
        $teamMembers = collect();
        if ($teamMember->hasPermission('team.view')) {
            $teamMembers = MindovaTeamMember::with('role')
                ->where('is_active', true)
                ->orderByDesc('last_login_at')
                ->get();
        }

        return view('mindova.dashboard', [
            'teamMember' => $teamMember,
            'stats' => $stats,
            'recentLogs' => $recentLogs,
            'teamMembers' => $teamMembers,
        ]);
    }
}
