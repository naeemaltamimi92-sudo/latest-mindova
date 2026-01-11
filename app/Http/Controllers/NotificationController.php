<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Get all notifications for authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Notification::where('user_id', $user->id);

        // Filter by read status
        if ($request->has('unread_only') && $request->unread_only) {
            $query->where('is_read', false);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->latest()->paginate(20);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Get unread notification count.
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $user = request()->user();

        if ($notification->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        $count = $this->notificationService->markAllAsRead($user);

        return response()->json([
            'message' => 'All notifications marked as read',
            'count' => $count,
        ]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        $user = request()->user();

        if ($notification->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted',
        ]);
    }
}
