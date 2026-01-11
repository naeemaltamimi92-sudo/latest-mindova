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
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_volunteers' => Volunteer::count(),
            'total_companies' => Company::count(),
            'total_challenges' => Challenge::count(),
            'active_challenges' => Challenge::where('status', 'active')->count(),
            'total_tasks' => Task::count(),
            'total_assignments' => TaskAssignment::count(),
            'completed_tasks' => TaskAssignment::whereNotNull('completed_at')->count(),
            'total_certificates' => Certificate::count(),
        ];

        // Recent activity
        $recentChallenges = Challenge::with(['company.user', 'volunteer.user'])
            ->latest()
            ->limit(5)
            ->get();

        $recentCertificates = Certificate::with(['user', 'challenge', 'company'])
            ->latest('issued_at')
            ->limit(5)
            ->get();

        // Top volunteers by reputation
        $topVolunteers = Volunteer::with('user')
            ->orderBy('reputation_score', 'desc')
            ->limit(5)
            ->get();

        // Active companies
        $activeCompanies = Company::with('user')
            ->has('challenges')
            ->withCount('challenges')
            ->orderBy('challenges_count', 'desc')
            ->limit(5)
            ->get();

        // Challenge status breakdown
        $challengesByStatus = Challenge::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Admin notifications (recent activity feed)
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        $unreadNotificationsCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentChallenges',
            'recentCertificates',
            'topVolunteers',
            'activeCompanies',
            'challengesByStatus',
            'notifications',
            'unreadNotificationsCount'
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
