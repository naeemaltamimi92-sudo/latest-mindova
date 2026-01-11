<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserGuidanceProgress;
use Illuminate\Support\Facades\Cache;

class GuidanceService
{
    /**
     * Get active guidance steps for user on current page.
     *
     * @param User $user
     * @param string $pageIdentifier
     * @return array
     */
    public function getActiveSteps(User $user, string $pageIdentifier): array
    {
        // Check if guidance is enabled globally
        if (!config('user_guidance.settings.enabled')) {
            return [];
        }

        // Get user role
        $role = $this->getUserRole($user);

        // Get page guidance configuration
        $pageGuidance = config("user_guidance.{$role}.{$pageIdentifier}", []);

        if (empty($pageGuidance)) {
            return [];
        }

        // Get max tooltips per page setting
        $maxTooltips = config('user_guidance.settings.max_tooltips_per_page', 3);

        // Filter out completed steps and check trigger conditions
        $activeSteps = [];
        foreach ($pageGuidance as $stepKey => $stepData) {
            // Stop if we've reached max tooltips
            if (count($activeSteps) >= $maxTooltips) {
                break;
            }

            $stepId = "{$pageIdentifier}.{$stepKey}";

            // Skip if already completed
            if (UserGuidanceProgress::isCompleted($user->id, $stepId)) {
                continue;
            }

            // Check if trigger condition is met
            if ($this->shouldShowStep($user, $stepData)) {
                $activeSteps[] = array_merge($stepData, [
                    'step_id' => $stepId,
                    'step_key' => $stepKey,
                ]);
            }
        }

        return $activeSteps;
    }

    /**
     * Determine user's role for guidance purposes.
     *
     * @param User $user
     * @return string
     */
    protected function getUserRole(User $user): string
    {
        // Assuming User model has a 'role' attribute
        // Adjust this based on your actual implementation
        if (isset($user->role)) {
            return $user->role === 'company' ? 'company' : 'volunteer';
        }

        // Fallback: check if user has volunteer or company profile
        if ($user->volunteer) {
            return 'volunteer';
        } elseif ($user->company) {
            return 'company';
        }

        // Default to volunteer
        return 'volunteer';
    }

    /**
     * Check if step should be shown based on trigger condition.
     *
     * @param User $user
     * @param array $stepData
     * @return bool
     */
    protected function shouldShowStep(User $user, array $stepData): bool
    {
        $trigger = $stepData['trigger'] ?? 'first_visit';

        switch ($trigger) {
            case 'first_visit':
                // Always show on first visit to this page
                return true;

            case 'profile_incomplete':
                // Show if user's profile is not complete
                return !$this->isProfileComplete($user);

            case 'team_invitation':
                // Show if user has pending team invitations
                return $this->hasUnacceptedTeamInvitations($user);

            case 'first_task_assigned':
                // Show if user has been assigned their first task
                return $this->hasActiveTasks($user);

            case 'has_active_assignments':
                // Show if user has active assignments
                return $this->hasActiveAssignments($user);

            case 'challenge_created':
                // Show if user has created at least one challenge
                return $this->hasChallenges($user);

            case 'challenge_ready_to_complete':
                // Show if user has a challenge ready for completion
                return $this->hasCompletedChallenges($user);

            default:
                // Unknown trigger, show by default
                return true;
        }
    }

    /**
     * Check if user's profile is complete.
     *
     * @param User $user
     * @return bool
     */
    protected function isProfileComplete(User $user): bool
    {
        // Check if volunteer profile is complete
        if ($user->volunteer) {
            return !empty($user->volunteer->cv_path) && !empty($user->volunteer->skills);
        }

        // Check if company profile is complete
        if ($user->company) {
            return !empty($user->company->company_name) && !empty($user->company->domain);
        }

        return false;
    }

    /**
     * Check if user has unaccepted team invitations.
     *
     * @param User $user
     * @return bool
     */
    protected function hasUnacceptedTeamInvitations(User $user): bool
    {
        return $user->teamMembers()
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if user has active tasks.
     *
     * @param User $user
     * @return bool
     */
    protected function hasActiveTasks(User $user): bool
    {
        return $user->taskAssignments()
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->exists();
    }

    /**
     * Check if user has active assignments.
     *
     * @param User $user
     * @return bool
     */
    protected function hasActiveAssignments(User $user): bool
    {
        return $user->taskAssignments()
            ->whereIn('status', ['accepted', 'in_progress'])
            ->exists();
    }

    /**
     * Check if user has created challenges.
     *
     * @param User $user
     * @return bool
     */
    protected function hasChallenges(User $user): bool
    {
        return $user->challenges()->exists();
    }

    /**
     * Check if user has completed challenges.
     *
     * @param User $user
     * @return bool
     */
    protected function hasCompletedChallenges(User $user): bool
    {
        return $user->challenges()
            ->whereIn('status', ['completed', 'delivered'])
            ->exists();
    }

    /**
     * Mark step as completed.
     *
     * @param User $user
     * @param string $stepId
     * @return void
     */
    public function completeStep(User $user, string $stepId): void
    {
        UserGuidanceProgress::markComplete($user->id, $stepId);

        // Clear cached guidance for this user
        Cache::forget("user_guidance_{$user->id}");
    }

    /**
     * Get user's guidance progress percentage.
     *
     * @param User $user
     * @return float
     */
    public function getProgressPercentage(User $user): float
    {
        $role = $this->getUserRole($user);
        $totalSteps = $this->getTotalStepsForRole($role);
        $completedSteps = UserGuidanceProgress::getCompletedCount($user->id);

        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100, 2) : 0;
    }

    /**
     * Get total number of guidance steps for a role.
     *
     * @param string $role
     * @return int
     */
    protected function getTotalStepsForRole(string $role): int
    {
        $guidance = config("user_guidance.{$role}", []);
        $total = 0;

        foreach ($guidance as $page => $steps) {
            $total += count($steps);
        }

        return $total;
    }

    /**
     * Reset user's guidance progress.
     *
     * @param User $user
     * @return void
     */
    public function resetProgress(User $user): void
    {
        UserGuidanceProgress::resetProgress($user->id);
        Cache::forget("user_guidance_{$user->id}");
    }

    /**
     * Check if user is new (for showing welcome guidance).
     *
     * @param User $user
     * @return bool
     */
    public function isNewUser(User $user): bool
    {
        // Consider user "new" if they completed less than 3 guidance steps
        $completedCount = UserGuidanceProgress::getCompletedCount($user->id);
        return $completedCount < 3;
    }
}
