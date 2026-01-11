<?php

namespace App\Services;

use App\Models\MindovaTeamMember;
use App\Models\MindovaRole;
use App\Models\MindovaAuditLog;
use App\Models\User;
use App\Mail\MindovaTeamInvitation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MindovaInvitationService
{
    /**
     * Generate a secure temporary password.
     */
    public function generateTemporaryPassword(): string
    {
        // Generate a secure password: 12 characters with mixed case, numbers, and special chars
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%^&*';

        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        // Fill remaining characters
        $allChars = $lowercase . $uppercase . $numbers . $special;
        for ($i = 0; $i < 8; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        return str_shuffle($password);
    }

    /**
     * Invite a new team member.
     */
    public function inviteTeamMember(
        string $email,
        string $name,
        int $roleId,
        MindovaTeamMember $invitedBy
    ): array {
        // Check if email already exists
        if (MindovaTeamMember::where('email', $email)->exists()) {
            return [
                'success' => false,
                'message' => __('A team member with this email already exists.'),
            ];
        }

        // Get the role
        $role = MindovaRole::find($roleId);
        if (!$role) {
            return [
                'success' => false,
                'message' => __('Invalid role selected.'),
            ];
        }

        // Check if inviter can assign this role
        if (!$invitedBy->role->canManageRole($role) && !$invitedBy->isOwner()) {
            return [
                'success' => false,
                'message' => __('You do not have permission to assign this role.'),
            ];
        }

        // Cannot create another owner
        if ($role->slug === 'owner' && MindovaTeamMember::ownerExists()) {
            return [
                'success' => false,
                'message' => __('An owner already exists. There can only be one owner.'),
            ];
        }

        // Generate temporary password
        $temporaryPassword = $this->generateTemporaryPassword();

        // Check if user exists
        $user = User::where('email', $email)->first();

        // Create team member
        $teamMember = MindovaTeamMember::create([
            'user_id' => $user?->id,
            'role_id' => $roleId,
            'email' => $email,
            'name' => $name,
            'temporary_password' => Hash::make($temporaryPassword),
            'password_changed' => false,
            'is_active' => true,
            'invited_at' => now(),
            'invited_by' => $invitedBy->id,
        ]);

        // Log the action
        MindovaAuditLog::logTeamMemberCreated($teamMember, $invitedBy);

        // Send invitation email
        try {
            Mail::to($email)->send(new MindovaTeamInvitation(
                $teamMember,
                $temporaryPassword
            ));
        } catch (\Exception $e) {
            // Log error but don't fail the invitation
            \Log::error('Failed to send team invitation email: ' . $e->getMessage());
        }

        return [
            'success' => true,
            'message' => __('Team member invited successfully. An invitation email has been sent.'),
            'team_member' => $teamMember,
        ];
    }

    /**
     * Resend invitation email.
     */
    public function resendInvitation(MindovaTeamMember $teamMember, MindovaTeamMember $resentBy): array
    {
        if ($teamMember->password_changed) {
            return [
                'success' => false,
                'message' => __('This team member has already activated their account.'),
            ];
        }

        // Generate new temporary password
        $temporaryPassword = $this->generateTemporaryPassword();

        $teamMember->update([
            'temporary_password' => Hash::make($temporaryPassword),
            'invited_at' => now(),
        ]);

        // Send invitation email
        try {
            Mail::to($teamMember->email)->send(new MindovaTeamInvitation(
                $teamMember,
                $temporaryPassword
            ));

            MindovaAuditLog::log(
                'team_member.invitation_resent',
                $resentBy,
                'team_member',
                $teamMember->id,
                null,
                null,
                "Invitation resent to '{$teamMember->name}'"
            );

            return [
                'success' => true,
                'message' => __('Invitation email has been resent.'),
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to resend team invitation email: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => __('Failed to send invitation email. Please try again.'),
            ];
        }
    }

    /**
     * Update team member role.
     */
    public function updateRole(
        MindovaTeamMember $teamMember,
        int $newRoleId,
        MindovaTeamMember $changedBy
    ): array {
        $oldRole = $teamMember->role;
        $newRole = MindovaRole::find($newRoleId);

        if (!$newRole) {
            return [
                'success' => false,
                'message' => __('Invalid role selected.'),
            ];
        }

        // Cannot change to owner role if owner exists
        if ($newRole->slug === 'owner' && !$oldRole->isOwner() && MindovaTeamMember::ownerExists()) {
            return [
                'success' => false,
                'message' => __('An owner already exists. There can only be one owner.'),
            ];
        }

        // Cannot remove owner role from self
        if ($oldRole->isOwner() && $teamMember->id === $changedBy->id) {
            return [
                'success' => false,
                'message' => __('You cannot remove the Owner role from yourself.'),
            ];
        }

        // Only owner can assign owner role
        if ($newRole->isOwner() && !$changedBy->isOwner()) {
            return [
                'success' => false,
                'message' => __('Only the Owner can transfer ownership.'),
            ];
        }

        $teamMember->update(['role_id' => $newRoleId]);

        MindovaAuditLog::logRoleChange($teamMember, $oldRole, $newRole, $changedBy);

        return [
            'success' => true,
            'message' => __('Role updated successfully.'),
        ];
    }

    /**
     * Deactivate a team member.
     */
    public function deactivate(MindovaTeamMember $teamMember, MindovaTeamMember $deactivatedBy): array
    {
        if (!$teamMember->is_active) {
            return [
                'success' => false,
                'message' => __('This team member is already deactivated.'),
            ];
        }

        // Cannot deactivate owner
        if ($teamMember->isOwner()) {
            return [
                'success' => false,
                'message' => __('Cannot deactivate the Owner account.'),
            ];
        }

        // Cannot deactivate self
        if ($teamMember->id === $deactivatedBy->id) {
            return [
                'success' => false,
                'message' => __('You cannot deactivate your own account.'),
            ];
        }

        $teamMember->deactivate();
        MindovaAuditLog::logDeactivation($teamMember, $deactivatedBy);

        return [
            'success' => true,
            'message' => __('Team member deactivated successfully.'),
        ];
    }

    /**
     * Activate a team member.
     */
    public function activate(MindovaTeamMember $teamMember, MindovaTeamMember $activatedBy): array
    {
        if ($teamMember->is_active) {
            return [
                'success' => false,
                'message' => __('This team member is already active.'),
            ];
        }

        $teamMember->activate();
        MindovaAuditLog::logActivation($teamMember, $activatedBy);

        return [
            'success' => true,
            'message' => __('Team member activated successfully.'),
        ];
    }

    /**
     * Remove a team member.
     */
    public function remove(MindovaTeamMember $teamMember, MindovaTeamMember $removedBy): array
    {
        // Cannot remove owner
        if ($teamMember->isOwner()) {
            return [
                'success' => false,
                'message' => __('Cannot remove the Owner account.'),
            ];
        }

        // Cannot remove self
        if ($teamMember->id === $removedBy->id) {
            return [
                'success' => false,
                'message' => __('You cannot remove your own account.'),
            ];
        }

        MindovaAuditLog::logDeletion($teamMember, $removedBy);
        $teamMember->delete();

        return [
            'success' => true,
            'message' => __('Team member removed successfully.'),
        ];
    }

    /**
     * Setup the initial owner.
     */
    public function setupOwner(string $email, string $name): array
    {
        if (MindovaTeamMember::ownerExists()) {
            return [
                'success' => false,
                'message' => __('An owner already exists.'),
            ];
        }

        $ownerRole = MindovaRole::findBySlug('owner');
        if (!$ownerRole) {
            return [
                'success' => false,
                'message' => __('Owner role not found. Please run migrations.'),
            ];
        }

        $user = User::where('email', $email)->first();
        $temporaryPassword = $this->generateTemporaryPassword();

        $teamMember = MindovaTeamMember::create([
            'user_id' => $user?->id,
            'role_id' => $ownerRole->id,
            'email' => $email,
            'name' => $name,
            'temporary_password' => Hash::make($temporaryPassword),
            'password_changed' => false,
            'is_active' => true,
            'invited_at' => now(),
        ]);

        // Send invitation email
        try {
            Mail::to($email)->send(new MindovaTeamInvitation(
                $teamMember,
                $temporaryPassword
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send owner invitation email: ' . $e->getMessage());
        }

        MindovaAuditLog::log(
            'owner.created',
            null,
            'team_member',
            $teamMember->id,
            null,
            ['email' => $email, 'name' => $name],
            "Owner account created for '{$name}'"
        );

        return [
            'success' => true,
            'message' => __('Owner account created successfully. An invitation email has been sent.'),
            'team_member' => $teamMember,
            'temp_password' => $temporaryPassword, // Return for development environments
        ];
    }
}
