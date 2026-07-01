<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Company;
use App\Models\Volunteer;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Certificate;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    private const CACHE_TTL_SECONDS = 300;

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Platform statistics, top volunteers/companies, and the recent-item
        // pools that feed both the "recent" widgets and the activity feed
        // below - cached together since they're all cheap-to-go-stale
        // snapshots, and this dashboard was otherwise running ~9 uncached
        // aggregate queries plus a second latest()-fetch of the same
        // challenges/certificates already loaded for the "recent" widgets.
        $snapshot = Cache::remember('admin.dashboard.snapshot', self::CACHE_TTL_SECONDS, function () {
            return [
                'stats' => [
                    'total_users' => User::count(),
                    'total_volunteers' => Volunteer::count(),
                    'total_companies' => Company::count(),
                    'total_challenges' => Challenge::count(),
                    'active_challenges' => Challenge::where('status', 'active')->count(),
                    'total_tasks' => Task::count(),
                    'total_assignments' => TaskAssignment::count(),
                    'completed_tasks' => TaskAssignment::whereNotNull('completed_at')->count(),
                    'total_certificates' => Certificate::count(),
                ],
                'latestChallenges' => Challenge::with(['company.user', 'volunteer.user'])
                    ->latest()
                    ->limit(15)
                    ->get(),
                'latestCertificates' => Certificate::with(['user', 'challenge', 'company'])
                    ->latest('issued_at')
                    ->limit(15)
                    ->get(),
                'latestCompletedAssignments' => TaskAssignment::with(['task.challenge', 'volunteer.user'])
                    ->whereNotNull('completed_at')
                    ->latest('completed_at')
                    ->limit(15)
                    ->get(),
                'topVolunteers' => Volunteer::with('user')
                    ->orderBy('reputation_score', 'desc')
                    ->limit(5)
                    ->get(),
                'activeCompanies' => Company::with('user')
                    ->has('challenges')
                    ->withCount('challenges')
                    ->orderBy('challenges_count', 'desc')
                    ->limit(5)
                    ->get(),
                'challengesByStatus' => Challenge::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray(),
            ];
        });

        $stats = $snapshot['stats'];
        $recentChallenges = $snapshot['latestChallenges']->take(5);
        $recentCertificates = $snapshot['latestCertificates']->take(5);
        $topVolunteers = $snapshot['topVolunteers'];
        $activeCompanies = $snapshot['activeCompanies'];
        $challengesByStatus = $snapshot['challengesByStatus'];

        // Admin notifications (recent activity feed) - per-admin, not cached
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        $unreadNotificationsCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        // Real platform activity feed (replaces the previous mocked feed),
        // built from the same challenge/certificate/assignment pools above
        // instead of re-querying them.
        $activityFeed = collect();

        foreach ($snapshot['latestChallenges'] as $challenge) {
            $activityFeed->push([
                'type' => 'challenge',
                'actor_name' => $challenge->company->company_name ?? $challenge->company->user->name ?? $challenge->volunteer->user->name ?? __('Community'),
                'actor_role' => $challenge->company ? __('Company') : ($challenge->volunteer ? __('Contributor') : __('Community')),
                'verb' => __('created the challenge'),
                'title' => $challenge->title,
                'status' => $challenge->status,
                'created_at' => $challenge->created_at,
                'url' => route('admin.challenges.show', $challenge),
            ]);
        }

        foreach ($snapshot['latestCertificates'] as $cert) {
            if (!$cert->user) {
                continue;
            }
            $activityFeed->push([
                'type' => 'certificate',
                'actor_name' => $cert->user->name,
                'actor_role' => __('Certified'),
                'verb' => __('earned a certificate for'),
                'title' => $cert->challenge->title ?? __('a challenge'),
                'status' => 'issued',
                'created_at' => $cert->issued_at ?? $cert->created_at,
                'url' => $cert->challenge ? route('admin.challenges.show', $cert->challenge) : null,
            ]);
        }

        foreach ($snapshot['latestCompletedAssignments'] as $assignment) {
            if (!$assignment->volunteer || !$assignment->volunteer->user || !$assignment->task || !$assignment->task->challenge) {
                continue;
            }
            $activityFeed->push([
                'type' => 'task',
                'actor_name' => $assignment->volunteer->user->name,
                'actor_role' => __('Contributor'),
                'verb' => __('completed a task on'),
                'title' => $assignment->task->challenge->title,
                'status' => 'completed',
                'created_at' => $assignment->completed_at,
                'url' => route('admin.challenges.show', $assignment->task->challenge),
            ]);
        }

        $activityFeed = $activityFeed->sortByDesc('created_at')->values()->take(20);

        return view('admin.dashboard', compact(
            'stats',
            'recentChallenges',
            'recentCertificates',
            'topVolunteers',
            'activeCompanies',
            'challengesByStatus',
            'notifications',
            'unreadNotificationsCount',
            'activityFeed'
        ));
    }

    /**
     * Mark all notifications as read.
     */
    public function markNotificationsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read');
    }
}
