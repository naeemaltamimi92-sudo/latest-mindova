<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ActiveTaskAssignmentResource;
use App\Http\Resources\Mobile\CompanyDashboardChallengeResource;
use App\Http\Resources\Mobile\PendingAssignmentResource;
use App\Models\Challenge;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Certificate;
use App\Models\SuccessStory;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MobileDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isVolunteer()) {
            return $this->volunteerDashboard($user);
        }

        if ($user->isCompany()) {
            return $this->companyDashboard($user);
        }

        return response()->json(['message' => 'Unknown user type.'], 422);
    }

    private function volunteerDashboard($user): JsonResponse
    {
        $volunteer = $user->volunteer;

        if (! $volunteer) {
            return response()->json(['requires_profile_setup' => true]);
        }

        $pendingAssignments = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'pending')
            ->with(['task.challenge'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($a) => (new PendingAssignmentResource($a))->resolve());

        $activeTasks = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress'])
            ->with(['task.challenge'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($a) => (new ActiveTaskAssignmentResource($a))->resolve());

        $completedCount  = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->where('invitation_status', 'completed')->count();
        $unreadCount = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        return response()->json([
            'user_type'           => 'volunteer',
            'profile_complete'    => (bool) $volunteer->field,
            'nda_signed'          => (bool) $volunteer->general_nda_signed,
            'pending_assignments' => $pendingAssignments,
            'active_tasks'        => $activeTasks,
            'stats' => [
                'pending_assignments' => $pendingAssignments->count(),
                'active_tasks'        => $activeTasks->count(),
                'completed_tasks'     => $completedCount,
                'reputation_score'    => $volunteer->reputation_score ?? 50,
                'trust_score'         => $volunteer->trust_score ?? 0,
                'unread_notifications'=> $unreadCount,
            ],
        ]);
    }

    private function companyDashboard($user): JsonResponse
    {
        $company = $user->company;

        if (! $company) {
            return response()->json(['requires_profile_setup' => true]);
        }

        $challenges = Challenge::where('company_id', $company->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($c) => (new CompanyDashboardChallengeResource($c))->resolve());

        $unreadCount = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        return response()->json([
            'user_type'        => 'company',
            'profile_complete' => (bool) $company->name,
            'challenges'       => $challenges,
            'stats' => [
                'total_challenges'       => $company->total_challenges_submitted ?? 0,
                'active_challenges'      => Challenge::where('company_id', $company->id)->where('status', 'active')->count(),
                'tasks_in_progress'      => 0,
                'completed_tasks'        => 0,
                'unread_notifications'   => $unreadCount,
            ],
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'data'        => $notifications->items(),
            'total'       => $notifications->total(),
            'current_page'=> $notifications->currentPage(),
            'last_page'   => $notifications->lastPage(),
        ]);
    }

    public function markNotificationRead(Request $request, $id): JsonResponse
    {
        $notification = Notification::where('user_id', $request->user()->id)
            ->findOrFail($id);
        $notification->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['message' => 'Marked as read.']);
    }

    public function markAllNotificationsRead(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
