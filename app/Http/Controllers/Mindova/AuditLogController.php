<?php

namespace App\Http\Controllers\Mindova;

use App\Http\Controllers\Controller;
use App\Models\MindovaAuditLog;
use App\Models\MindovaTeamMember;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    /**
     * Show audit logs.
     */
    public function index(Request $request)
    {
        $query = MindovaAuditLog::with('teamMember.role')
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by team member
        if ($request->filled('team_member_id')) {
            $query->where('team_member_id', $request->team_member_id);
        }

        $logs = $query->paginate(50);

        // Get stats for the header
        $stats = [
            'logins_today' => MindovaAuditLog::where('action', 'login')
                ->whereDate('created_at', Carbon::today())
                ->count(),
            'actions_today' => MindovaAuditLog::whereDate('created_at', Carbon::today())
                ->count(),
        ];

        // Get team members for filter dropdown
        $teamMembers = MindovaTeamMember::with('role')
            ->orderBy('name')
            ->get();

        return view('mindova.audit.index', [
            'logs' => $logs,
            'filters' => $request->only(['action', 'from_date', 'to_date', 'team_member_id']),
            'stats' => $stats,
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Export audit logs.
     */
    public function export(Request $request)
    {
        $query = MindovaAuditLog::with('teamMember')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->get();

        $csvContent = "Date,Time,Action,Team Member,Description,IP Address\n";

        foreach ($logs as $log) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                $log->created_at->format('Y-m-d'),
                $log->created_at->format('H:i:s'),
                str_replace(',', ';', $log->action_label),
                str_replace(',', ';', $log->teamMember?->name ?? 'System'),
                str_replace(',', ';', $log->description ?? ''),
                $log->ip_address ?? ''
            );
        }

        $filename = 'audit-logs-' . now()->format('Y-m-d-His') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
