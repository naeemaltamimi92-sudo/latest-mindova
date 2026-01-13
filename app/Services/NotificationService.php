<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'is_read' => false,
        ]);
    }

    /**
     * Send a notification to all admin users.
     */
    public function notifyAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null
    ): void {
        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            $this->send($admin, $type, $title, $message, $actionUrl);
        }
    }

    /**
     * Notify volunteer of new task assignment.
     */
    public function notifyTaskAssignment($assignment): void
    {
        $volunteer = $assignment->volunteer;
        $task = $assignment->task;
        $challenge = $task->challenge;

        $this->send(
            user: $volunteer->user,
            type: 'task_assignment',
            title: 'New Task Invitation',
            message: "You've been invited to work on: \"{$task->title}\" in challenge \"{$challenge->title}\". Match score: {$assignment->ai_match_score}%",
            actionUrl: "/assignments"
        );

        // Notify admins
        $this->notifyAdmins(
            type: 'admin_task_assignment',
            title: 'Task Assigned',
            message: "Task \"{$task->title}\" assigned to {$volunteer->user->name} (Match: {$assignment->ai_match_score}%) in challenge \"{$challenge->title}\"",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify volunteer of idea scoring completion.
     */
    public function notifyIdeaScored($idea): void
    {
        $volunteer = $idea->volunteer;
        $challenge = $idea->challenge;

        $feedback = $idea->ai_feedback;
        $feedbackText = $feedback['feedback'] ?? 'Your idea has been evaluated.';

        $this->send(
            user: $volunteer->user,
            type: 'idea_scored',
            title: 'Your Idea Has Been Evaluated',
            message: "Your idea \"{$idea->title}\" for challenge \"{$challenge->title}\" received an AI score of {$idea->ai_score}/100. {$feedbackText}",
            actionUrl: "/ideas/{$idea->id}"
        );
    }

    /**
     * Notify company when challenge analysis is complete.
     */
    public function notifyChallengeAnalyzed($challenge): void
    {
        // Get the owner (either company or volunteer)
        $ownerUser = null;
        $ownerName = 'Unknown';
        $actionUrl = "/challenges/{$challenge->id}";

        if ($challenge->isVolunteerSubmitted() && $challenge->volunteer && $challenge->volunteer->user) {
            $ownerUser = $challenge->volunteer->user;
            $ownerName = $challenge->volunteer->user->name ?? 'Volunteer';
            $actionUrl = "/my-challenges/{$challenge->id}";
        } elseif ($challenge->company && $challenge->company->user) {
            $ownerUser = $challenge->company->user;
            $ownerName = $challenge->company->company_name ?? 'Company';
        }

        $message = $challenge->challenge_type === 'community_discussion'
            ? "Your challenge \"{$challenge->title}\" has been analyzed and is now open for community ideas."
            : "Your challenge \"{$challenge->title}\" has been analyzed and decomposed into tasks. Volunteer matching is in progress.";

        // Notify challenge owner
        if ($ownerUser) {
            $this->send(
                user: $ownerUser,
                type: 'challenge_analyzed',
                title: 'Challenge Analysis Complete',
                message: $message,
                actionUrl: $actionUrl
            );
        }

        // Notify admins
        $adminMessage = $challenge->challenge_type === 'community_discussion'
            ? "Challenge \"{$challenge->title}\" by {$ownerName} analyzed - open for community ideas."
            : "Challenge \"{$challenge->title}\" by {$ownerName} analyzed and decomposed into tasks.";

        $this->notifyAdmins(
            type: 'admin_challenge_analyzed',
            title: 'Challenge Analysis Complete',
            message: $adminMessage,
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify company when a volunteer accepts a task.
     * Note: Volunteer identity is hidden to maintain NDA confidentiality.
     */
    public function notifyTaskAccepted($assignment): void
    {
        $task = $assignment->task;
        $challenge = $task->challenge;
        $company = $challenge->company;
        $volunteer = $assignment->volunteer;

        $this->send(
            user: $company->user,
            type: 'task_accepted',
            title: 'Task Invitation Accepted',
            message: "A qualified volunteer has accepted the task \"{$task->title}\" in your challenge \"{$challenge->title}\". Work will begin shortly.",
            actionUrl: "/challenges/{$challenge->id}"
        );

        // Notify admins (admins can see volunteer identity)
        $this->notifyAdmins(
            type: 'admin_task_accepted',
            title: 'Task Accepted',
            message: "{$volunteer->user->name} accepted task \"{$task->title}\" in challenge \"{$challenge->title}\"",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify company when a task is completed.
     * Note: Volunteer identity is hidden to maintain NDA confidentiality.
     */
    public function notifyTaskCompleted($assignment): void
    {
        $task = $assignment->task;
        $challenge = $task->challenge;
        $company = $challenge->company;
        $volunteer = $assignment->volunteer;

        $this->send(
            user: $company->user,
            type: 'task_completed',
            title: 'Task Completed',
            message: "The task \"{$task->title}\" has been completed and submitted for review. Time spent: {$assignment->actual_hours} hours.",
            actionUrl: "/challenges/{$challenge->id}"
        );

        // Notify admins
        $this->notifyAdmins(
            type: 'admin_task_completed',
            title: 'Task Completed',
            message: "{$volunteer->user->name} completed task \"{$task->title}\" ({$assignment->actual_hours}h) in challenge \"{$challenge->title}\"",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify company when a new idea is submitted.
     * Note: Volunteer identity is hidden to maintain NDA confidentiality.
     */
    public function notifyNewIdea($idea): void
    {
        $challenge = $idea->challenge;
        $company = $challenge->company;
        $volunteer = $idea->volunteer;

        $this->send(
            user: $company->user,
            type: 'new_idea',
            title: 'New Idea Submitted',
            message: "A new idea \"{$idea->title}\" has been submitted for your challenge \"{$challenge->title}\".",
            actionUrl: "/challenges/{$challenge->id}/ideas"
        );

        // Notify admins
        $this->notifyAdmins(
            type: 'admin_new_idea',
            title: 'New Idea Submitted',
            message: "{$volunteer->user->name} submitted idea \"{$idea->title}\" for challenge \"{$challenge->title}\"",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify volunteer when their idea receives votes.
     */
    public function notifyIdeaVoted($idea, $newVoteCount): void
    {
        // Only notify on significant vote milestones
        $milestones = [5, 10, 25, 50, 100];

        if (in_array($newVoteCount, $milestones)) {
            $volunteer = $idea->volunteer;

            $this->send(
                user: $volunteer->user,
                type: 'idea_votes',
                title: 'Your Idea is Getting Attention!',
                message: "Your idea \"{$idea->title}\" has reached {$newVoteCount} community votes!",
                actionUrl: "/ideas/{$idea->id}"
            );
        }
    }

    /**
     * Notify company when a challenge is fully completed with all solutions.
     */
    public function notifyChallengeCompleted($challenge, $aggregatedSolutions, $averageQuality): void
    {
        $company = $challenge->company;
        $taskCount = count($aggregatedSolutions);

        $this->send(
            user: $company->user,
            type: 'challenge_completed',
            title: 'Challenge Completed!',
            message: "Your challenge \"{$challenge->title}\" has been completed! All {$taskCount} tasks have approved solutions with an average quality score of {$averageQuality}/100. View the complete solution package now.",
            actionUrl: "/challenges/{$challenge->id}"
        );

        // Notify admins
        $this->notifyAdmins(
            type: 'admin_challenge_completed',
            title: 'Challenge Completed',
            message: "Challenge \"{$challenge->title}\" by {$company->company_name} completed! {$taskCount} tasks, avg quality: {$averageQuality}/100",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify admins when a new challenge is created/submitted.
     */
    public function notifyChallengeCreated($challenge): void
    {
        $company = $challenge->company;

        $this->notifyAdmins(
            type: 'admin_challenge_created',
            title: 'New Challenge Submitted',
            message: "New challenge \"{$challenge->title}\" submitted by {$company->company_name}. AI analysis in progress.",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Notify admins when certificates are issued.
     */
    public function notifyCertificatesIssued($challenge, $certificateCount): void
    {
        $company = $challenge->company;

        $this->notifyAdmins(
            type: 'admin_certificates_issued',
            title: 'Certificates Issued',
            message: "{$certificateCount} certificate(s) issued for challenge \"{$challenge->title}\" by {$company->company_name}",
            actionUrl: "/admin/challenges/{$challenge->id}"
        );
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get unread notification count for a user.
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }
}
