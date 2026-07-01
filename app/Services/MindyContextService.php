<?php

namespace App\Services;

use App\Models\ReputationHistory;
use App\Models\TaskAssignment;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\WorkSubmission;

/**
 * Builds Mindy's runtime context strictly from real data. No field here
 * should ever be invented - if a signal genuinely isn't modeled (e.g.
 * certificates have no "pending claim" concept in this schema), it's
 * simply left out rather than faked.
 */
class MindyContextService
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function build(User $user, ?string $currentPage): array
    {
        return [
            'user_role' => $this->resolveRole($user),
            'current_page' => $currentPage ?: 'unknown',
            'profile_percent' => $this->profileCompletionPercent($user),
            'pending_items' => $this->pendingItems($user),
            'recent_activity' => $this->recentActivity($user),
        ];
    }

    private function resolveRole(User $user): string
    {
        return match (true) {
            $user->isAdmin() => 'Administrator',
            $user->isCompany() => 'Company',
            $user->isVolunteer() => 'Community Member',
            default => 'Community Member',
        };
    }

    private function profileCompletionPercent(User $user): int
    {
        $totalFields = 5;
        $completed = 0;

        if ($user->name) {
            $completed++;
        }
        if ($user->email) {
            $completed++;
        }

        if ($user->isVolunteer() && $user->volunteer) {
            $volunteer = $user->volunteer;
            if ($volunteer->profile_picture) {
                $completed++;
            }
            if ($volunteer->bio) {
                $completed++;
            }
            if ($volunteer->skills()->count() > 0) {
                $completed++;
            }
        } elseif ($user->isCompany() && $user->company) {
            $company = $user->company;
            if ($company->logo_path) {
                $completed++;
            }
            if ($company->description) {
                $completed++;
            }
            if ($company->industry) {
                $completed++;
            }
        }

        return (int) round(($completed / $totalFields) * 100);
    }

    private function pendingItems(User $user): array
    {
        $items = [];

        $unread = $this->notifications->getUnreadCount($user);
        if ($unread > 0) {
            $items[] = "{$unread} unread notification" . ($unread === 1 ? '' : 's');
        }

        if ($user->isVolunteer() && $user->volunteer) {
            $volunteerId = $user->volunteer->id;

            $invited = TaskAssignment::where('volunteer_id', $volunteerId)
                ->where('invitation_status', 'invited')
                ->count();
            if ($invited > 0) {
                $items[] = "{$invited} task invitation" . ($invited === 1 ? '' : 's') . ' awaiting a response';
            }

            $activeTasks = TaskAssignment::where('volunteer_id', $volunteerId)
                ->whereIn('invitation_status', ['accepted', 'in_progress'])
                ->count();
            if ($activeTasks > 0) {
                $items[] = "{$activeTasks} task" . ($activeTasks === 1 ? '' : 's') . ' currently in progress';
            }

            $teamInvites = TeamMember::where('volunteer_id', $volunteerId)
                ->where('status', 'pending')
                ->count();
            if ($teamInvites > 0) {
                $items[] = "{$teamInvites} pending team invitation" . ($teamInvites === 1 ? '' : 's');
            }
        }

        if ($user->isCompany() && $user->company) {
            $pendingReviews = WorkSubmission::where('status', 'submitted')
                ->whereIn('task_id', $user->company->tasks()->pluck('tasks.id'))
                ->count();
            if ($pendingReviews > 0) {
                $items[] = "{$pendingReviews} volunteer submission" . ($pendingReviews === 1 ? '' : 's') . ' awaiting your review';
            }
        }

        return $items;
    }

    private function recentActivity(User $user): array
    {
        $activity = [];

        if ($user->isVolunteer() && $user->volunteer) {
            $recentStars = ReputationHistory::where('volunteer_id', $user->volunteer->id)
                ->latest('created_at')
                ->limit(3)
                ->get();

            foreach ($recentStars as $entry) {
                $label = str_replace('_', ' ', $entry->reason);
                $activity[] = "earned {$entry->change_amount} Stars for {$label}";
            }
        }

        if ($user->isCompany() && $user->company) {
            $recentChallenges = $user->company->challenges()
                ->latest('created_at')
                ->limit(3)
                ->get(['title', 'status']);

            foreach ($recentChallenges as $challenge) {
                $activity[] = "submitted challenge \"{$challenge->title}\" (status: {$challenge->status})";
            }
        }

        return $activity;
    }
}
