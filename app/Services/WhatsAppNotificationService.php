<?php

namespace App\Services;

use App\Models\User;
use App\Models\WhatsAppNotification;
use App\Jobs\SendWhatsAppNotificationJob;
use Illuminate\Support\Facades\DB;
use Exception;

class WhatsAppNotificationService
{
    /**
     * Queue a team invitation notification.
     *
     * @param User $user
     * @param int $teamId
     * @return WhatsAppNotification|null
     */
    public static function queueTeamInvitation(User $user, int $teamId): ?WhatsAppNotification
    {
        return self::queueNotification(
            user: $user,
            type: 'team_invite',
            entityType: 'team',
            entityId: $teamId,
            templateName: 'team_invite'
        );
    }

    /**
     * Queue a task assignment notification.
     *
     * @param User $user
     * @param int $taskId
     * @return WhatsAppNotification|null
     */
    public static function queueTaskAssignment(User $user, int $taskId): ?WhatsAppNotification
    {
        return self::queueNotification(
            user: $user,
            type: 'task_assigned',
            entityType: 'task',
            entityId: $taskId,
            templateName: 'task_assigned'
        );
    }

    /**
     * Queue a critical update notification.
     *
     * @param User $user
     * @param int $challengeId
     * @return WhatsAppNotification|null
     */
    public static function queueCriticalUpdate(User $user, int $challengeId): ?WhatsAppNotification
    {
        return self::queueNotification(
            user: $user,
            type: 'critical_update',
            entityType: 'challenge',
            entityId: $challengeId,
            templateName: 'critical_update'
        );
    }

    /**
     * Queue a WhatsApp notification with deduplication.
     *
     * @param User $user
     * @param string $type
     * @param string $entityType
     * @param int $entityId
     * @param string $templateName
     * @return WhatsAppNotification|null
     */
    private static function queueNotification(
        User $user,
        string $type,
        string $entityType,
        int $entityId,
        string $templateName
    ): ?WhatsAppNotification {
        // Check if user has WhatsApp enabled
        if (!$user->hasWhatsAppEnabled()) {
            return null;
        }

        try {
            // Use database transaction for deduplication
            return DB::transaction(function () use ($user, $type, $entityType, $entityId, $templateName) {
                // Try to create notification (will fail if duplicate due to unique constraint)
                $notification = WhatsAppNotification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                    'template_name' => $templateName,
                    'status' => 'queued',
                ]);

                // Dispatch the job with delay
                SendWhatsAppNotificationJob::dispatch($notification);

                return $notification;
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // If unique constraint violation, notification already exists
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
                return null; // Already queued, skip
            }

            // Re-throw other exceptions
            throw $e;
        }
    }

    /**
     * Get notification statistics for a user.
     *
     * @param User $user
     * @return array
     */
    public static function getStats(User $user): array
    {
        return [
            'total' => $user->whatsappNotifications()->count(),
            'queued' => $user->whatsappNotifications()->queued()->count(),
            'sent' => $user->whatsappNotifications()->sent()->count(),
            'failed' => $user->whatsappNotifications()->failed()->count(),
        ];
    }
}
