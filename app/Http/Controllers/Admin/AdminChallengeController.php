<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Company;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminChallengeController extends Controller
{
    /**
     * Challenge statuses with labels and colors.
     */
    protected array $statuses = [
        'submitted' => ['label' => 'Submitted', 'color' => 'yellow', 'icon' => 'clock'],
        'analyzing' => ['label' => 'Analyzing', 'color' => 'blue', 'icon' => 'cog'],
        'active' => ['label' => 'Active', 'color' => 'green', 'icon' => 'play'],
        'in_progress' => ['label' => 'In Progress', 'color' => 'indigo', 'icon' => 'refresh'],
        'completed' => ['label' => 'Completed', 'color' => 'emerald', 'icon' => 'check-circle'],
        'delivered' => ['label' => 'Delivered', 'color' => 'teal', 'icon' => 'badge-check'],
        'archived' => ['label' => 'Archived', 'color' => 'gray', 'icon' => 'archive'],
        'rejected' => ['label' => 'Rejected', 'color' => 'red', 'icon' => 'x-circle'],
    ];

    /**
     * Challenge types.
     */
    protected array $challengeTypes = [
        'community_discussion' => 'Community Discussion',
        'team_execution' => 'Team Execution',
    ];

    /**
     * Display challenges dashboard with analytics.
     */
    public function index(Request $request)
    {
        $query = Challenge::with(['company.user', 'volunteer.user', 'workstreams.tasks']);

        // Filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('challenge_type')) {
            $query->where('challenge_type', $request->challenge_type);
        }

        if ($request->filled('source')) {
            if ($request->source === 'company') {
                $query->whereNotNull('company_id');
            } elseif ($request->source === 'volunteer') {
                $query->whereNotNull('volunteer_id');
            }
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('original_description', 'like', '%' . $search . '%')
                  ->orWhere('refined_brief', 'like', '%' . $search . '%')
                  ->orWhere('field', 'like', '%' . $search . '%')
                  ->orWhereHas('company', function($cq) use ($search) {
                      $cq->where('company_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['created_at', 'updated_at', 'title', 'status', 'complexity_level', 'score'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $challenges = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = $this->getStatistics();

        // Get companies for filter dropdown
        $companies = Company::whereHas('challenges')->with('user')->get();

        return view('admin.challenges.index', compact(
            'challenges',
            'stats',
            'companies'
        ))->with([
            'statuses' => $this->statuses,
            'challengeTypes' => $this->challengeTypes,
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    protected function getStatistics(): array
    {
        return Cache::remember('admin.challenges.stats', 300, function () {
            $total = Challenge::count();
            $byStatus = Challenge::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $byType = Challenge::selectRaw('challenge_type, COUNT(*) as count')
                ->whereNotNull('challenge_type')
                ->groupBy('challenge_type')
                ->pluck('count', 'challenge_type')
                ->toArray();

            $thisMonth = Challenge::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $lastMonth = Challenge::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();

            $growthRate = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;

            $avgCompletionTime = Challenge::whereNotNull('completed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, completed_at)) as avg_days')
                ->value('avg_days') ?? 0;

            $totalTasks = DB::table('tasks')->count();
            $completedTasks = DB::table('tasks')->where('status', 'completed')->count();
            $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

            $recentActivity = Challenge::where('updated_at', '>=', now()->subDays(7))->count();

            $avgScore = Challenge::whereNotNull('score')->avg('score') ?? 0;

            return [
                'total' => $total,
                'by_status' => $byStatus,
                'by_type' => $byType,
                'this_month' => $thisMonth,
                'last_month' => $lastMonth,
                'growth_rate' => $growthRate,
                'avg_completion_days' => round($avgCompletionTime, 1),
                'task_completion_rate' => $taskCompletionRate,
                'recent_activity' => $recentActivity,
                'avg_score' => round($avgScore, 1),
                'pending_review' => $byStatus['submitted'] ?? 0,
            ];
        });
    }

    /**
     * Display a single challenge with full details.
     */
    public function show(Challenge $challenge)
    {
        $challenge->load([
            'company.user',
            'volunteer.user',
            'workstreams.tasks.assignments.volunteer.user',
            'teams.members.volunteer.user',
            'ideas.volunteer.user',
            'certificates.user',
            'attachments',
            'challengeAnalyses',
            'comments.user',
        ]);

        // Load admin logs
        $adminLogs = DB::table('challenge_admin_logs')
            ->where('challenge_id', $challenge->id)
            ->join('users', 'challenge_admin_logs.admin_id', '=', 'users.id')
            ->select('challenge_admin_logs.*', 'users.name as admin_name')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Log this view
        $this->logAdminAction($challenge, 'viewed', 'Challenge details viewed');

        // Increment view count
        $challenge->increment('view_count');

        return view('admin.challenges.show', compact(
            'challenge',
            'adminLogs'
        ))->with([
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Delete a challenge with notification to owner.
     */
    public function destroy(Request $request, Challenge $challenge)
    {
        $request->validate([
            'deletion_reason' => 'required|string|min:10|max:1000',
        ]);

        $deletionReason = $request->deletion_reason;
        $challengeTitle = $challenge->title;

        // Get the owner (company or volunteer)
        $owner = $challenge->owner;
        $ownerUser = $challenge->owner_user;

        // Send notification to owner before deleting
        if ($ownerUser) {
            $this->sendDeletionNotification($ownerUser, $challengeTitle, $deletionReason);
        }

        // Log the deletion
        $this->logAdminAction($challenge, 'deleted', "Challenge permanently deleted. Reason: {$deletionReason}");

        // Delete related data
        $challenge->workstreams()->delete();
        $challenge->tasks()->delete();
        $challenge->challengeAnalyses()->delete();
        $challenge->comments()->delete();
        $challenge->attachments()->delete();

        $challenge->delete();

        Cache::forget('admin.challenges.stats');

        return redirect()->route('admin.challenges.index')
            ->with('success', __('Challenge deleted successfully. The owner has been notified.'));
    }

    /**
     * Bulk delete challenges.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'challenge_ids' => 'required|array',
            'challenge_ids.*' => 'exists:challenges,id',
            'deletion_reason' => 'required|string|min:10|max:1000',
        ]);

        $challenges = Challenge::whereIn('id', $request->challenge_ids)->get();
        $deletionReason = $request->deletion_reason;
        $count = 0;

        foreach ($challenges as $challenge) {
            $ownerUser = $challenge->owner_user;

            // Send notification to owner
            if ($ownerUser) {
                $this->sendDeletionNotification($ownerUser, $challenge->title, $deletionReason);
            }

            // Log the deletion
            $this->logAdminAction($challenge, 'deleted', "Challenge deleted via bulk action. Reason: {$deletionReason}");

            // Delete related data
            $challenge->workstreams()->delete();
            $challenge->tasks()->delete();
            $challenge->challengeAnalyses()->delete();
            $challenge->comments()->delete();
            $challenge->attachments()->delete();

            $challenge->delete();
            $count++;
        }

        Cache::forget('admin.challenges.stats');

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => __(':count challenges deleted successfully. Owners have been notified.', ['count' => $count]),
        ]);
    }

    /**
     * Send deletion notification to the challenge owner.
     */
    protected function sendDeletionNotification($user, string $challengeTitle, string $reason): void
    {
        try {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'challenge_deleted',
                'title' => __('Challenge Deleted'),
                'message' => __('Your challenge ":title" has been removed by the platform administrator.', ['title' => $challengeTitle]),
                'data' => json_encode([
                    'challenge_title' => $challengeTitle,
                    'deletion_reason' => $reason,
                    'deleted_at' => now()->toISOString(),
                    'deleted_by' => auth()->user()->name,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deletion notification', [
                'user_id' => $user->id,
                'challenge_title' => $challengeTitle,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Export challenges.
     */
    public function export(Request $request)
    {
        $query = Challenge::with(['company.user', 'volunteer.user']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $challenges = $query->orderBy('created_at', 'desc')->get();

        $format = $request->get('format', 'csv');

        if ($format === 'json') {
            return response()->json($challenges);
        }

        // CSV Export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="challenges_export_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($challenges) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Title', 'Status', 'Type', 'Complexity',
                'Score', 'Field', 'Submitter', 'Submitter Type',
                'Created At', 'Completed At', 'Tasks Count'
            ]);

            foreach ($challenges as $challenge) {
                $submitter = $challenge->company
                    ? ($challenge->company->company_name ?? $challenge->company->user->name ?? 'Unknown')
                    : ($challenge->volunteer->user->name ?? 'Community');

                fputcsv($file, [
                    $challenge->id,
                    $challenge->title,
                    $challenge->status,
                    $challenge->challenge_type ?? 'N/A',
                    $challenge->complexity_level ?? 'N/A',
                    $challenge->score ?? 'N/A',
                    $challenge->field ?? 'N/A',
                    $submitter,
                    $challenge->company_id ? 'Company' : ($challenge->volunteer_id ? 'Volunteer' : 'Community'),
                    $challenge->created_at->format('Y-m-d H:i:s'),
                    $challenge->completed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $challenge->tasks()->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show analytics dashboard.
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days

        // Challenges over time
        $challengesOverTime = Challenge::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution
        $statusDistribution = Challenge::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Type distribution
        $typeDistribution = Challenge::selectRaw('challenge_type, COUNT(*) as count')
            ->whereNotNull('challenge_type')
            ->groupBy('challenge_type')
            ->get();

        // Top companies
        $topCompanies = Company::withCount('challenges')
            ->having('challenges_count', '>', 0)
            ->orderByDesc('challenges_count')
            ->limit(10)
            ->get();

        // Completion rate over time
        $completionStats = Challenge::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as total,
                SUM(CASE WHEN status IN ("completed", "delivered") THEN 1 ELSE 0 END) as completed
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Average scores by field
        $scoresByField = Challenge::selectRaw('field, AVG(score) as avg_score, COUNT(*) as count')
            ->whereNotNull('field')
            ->whereNotNull('score')
            ->groupBy('field')
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get();

        // Task completion trends
        $taskStats = DB::table('tasks')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as created,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
            ')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Performance metrics
        $metrics = [
            'avg_analysis_time' => $this->getAverageAnalysisTime(),
            'avg_completion_time' => $this->getAverageCompletionTime(),
            'volunteer_engagement' => $this->getVolunteerEngagement(),
            'success_rate' => $this->getSuccessRate(),
        ];

        return view('admin.challenges.analytics', compact(
            'challengesOverTime',
            'statusDistribution',
            'typeDistribution',
            'topCompanies',
            'completionStats',
            'scoresByField',
            'taskStats',
            'metrics',
            'period'
        ));
    }

    /**
     * Log admin action.
     */
    protected function logAdminAction(Challenge $challenge, string $action, string $description): void
    {
        DB::table('challenge_admin_logs')->insert([
            'challenge_id' => $challenge->id,
            'admin_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'old_values' => null,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Get average analysis time in hours.
     */
    protected function getAverageAnalysisTime(): float
    {
        $avg = Challenge::whereNotNull('ai_analyzed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, ai_analyzed_at)) as avg_hours')
            ->value('avg_hours');

        return round($avg ?? 0, 1);
    }

    /**
     * Get average completion time in days.
     */
    protected function getAverageCompletionTime(): float
    {
        $avg = Challenge::whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, completed_at)) as avg_days')
            ->value('avg_days');

        return round($avg ?? 0, 1);
    }

    /**
     * Get volunteer engagement percentage.
     */
    protected function getVolunteerEngagement(): float
    {
        $totalAssignments = DB::table('task_assignments')->count();
        $acceptedAssignments = DB::table('task_assignments')
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'completed'])
            ->count();

        return $totalAssignments > 0 ? round(($acceptedAssignments / $totalAssignments) * 100, 1) : 0;
    }

    /**
     * Get challenge success rate.
     */
    protected function getSuccessRate(): float
    {
        $total = Challenge::whereIn('status', ['completed', 'delivered', 'archived', 'rejected'])->count();
        $successful = Challenge::whereIn('status', ['completed', 'delivered'])->count();

        return $total > 0 ? round(($successful / $total) * 100, 1) : 0;
    }
}
