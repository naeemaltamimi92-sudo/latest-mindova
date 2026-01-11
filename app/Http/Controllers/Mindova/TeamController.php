<?php

namespace App\Http\Controllers\Mindova;

use App\Http\Controllers\Controller;
use App\Models\MindovaTeamMember;
use App\Models\MindovaRole;
use App\Models\MindovaAuditLog;
use App\Services\MindovaInvitationService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected MindovaInvitationService $invitationService;

    public function __construct(MindovaInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Show team members list.
     */
    public function index(Request $request)
    {
        $teamMember = $request->mindovaTeamMember;

        $members = MindovaTeamMember::with('role', 'invitedByMember')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = MindovaRole::orderBy('level', 'desc')->get();

        return view('mindova.team.index', [
            'members' => $members,
            'roles' => $roles,
            'currentMember' => $teamMember,
        ]);
    }

    /**
     * Show invite form.
     */
    public function create(Request $request)
    {
        $teamMember = $request->mindovaTeamMember;

        // Get roles that the current member can assign
        $roles = MindovaRole::where('level', '<', $teamMember->role->level)
            ->orWhere(function ($query) use ($teamMember) {
                // Owner can assign any role except owner if one exists
                if ($teamMember->isOwner()) {
                    $query->where('slug', '!=', 'owner');
                }
            })
            ->orderBy('level', 'desc')
            ->get();

        // Owner can also assign owner role if no owner exists
        if ($teamMember->isOwner() && !MindovaTeamMember::ownerExists()) {
            $roles = MindovaRole::orderBy('level', 'desc')->get();
        }

        $ownerExists = MindovaTeamMember::ownerExists();

        return view('mindova.team.create', [
            'roles' => $roles,
            'currentMember' => $teamMember,
            'ownerExists' => $ownerExists,
        ]);
    }

    /**
     * Store new team member (invite).
     */
    public function store(Request $request)
    {
        $teamMember = $request->mindovaTeamMember;

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'exists:mindova_roles,id'],
        ]);

        $result = $this->invitationService->inviteTeamMember(
            $validated['email'],
            $validated['name'],
            $validated['role_id'],
            $teamMember
        );

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('mindova.team.index')
            ->with('success', $result['message']);
    }

    /**
     * Show team member details.
     */
    public function show(Request $request, MindovaTeamMember $member)
    {
        $member->load('role', 'invitedByMember', 'auditLogs');

        return view('mindova.team.show', [
            'member' => $member,
            'currentMember' => $request->mindovaTeamMember,
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit(Request $request, MindovaTeamMember $member)
    {
        $teamMember = $request->mindovaTeamMember;

        // Get roles that the current member can assign
        $roles = MindovaRole::orderBy('level', 'desc')->get();

        return view('mindova.team.edit', [
            'member' => $member,
            'roles' => $roles,
            'currentMember' => $teamMember,
        ]);
    }

    /**
     * Update team member.
     */
    public function update(Request $request, MindovaTeamMember $member)
    {
        $teamMember = $request->mindovaTeamMember;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'exists:mindova_roles,id'],
        ]);

        // Update name
        if ($member->name !== $validated['name']) {
            $member->update(['name' => $validated['name']]);
        }

        // Update role if changed
        if ($member->role_id !== (int) $validated['role_id']) {
            $result = $this->invitationService->updateRole(
                $member,
                $validated['role_id'],
                $teamMember
            );

            if (!$result['success']) {
                return back()->withInput()->with('error', $result['message']);
            }
        }

        return redirect()->route('mindova.team.index')
            ->with('success', __('Team member updated successfully.'));
    }

    /**
     * Deactivate team member.
     */
    public function deactivate(Request $request, MindovaTeamMember $member)
    {
        $teamMember = $request->mindovaTeamMember;

        $result = $this->invitationService->deactivate($member, $teamMember);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Activate team member.
     */
    public function activate(Request $request, MindovaTeamMember $member)
    {
        $teamMember = $request->mindovaTeamMember;

        $result = $this->invitationService->activate($member, $teamMember);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Remove team member.
     */
    public function destroy(Request $request, MindovaTeamMember $member)
    {
        $teamMember = $request->mindovaTeamMember;

        $result = $this->invitationService->remove($member, $teamMember);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('mindova.team.index')
            ->with('success', $result['message']);
    }

    /**
     * Resend invitation.
     */
    public function resendInvitation(Request $request, MindovaTeamMember $member)
    {
        $teamMember = $request->mindovaTeamMember;

        $result = $this->invitationService->resendInvitation($member, $teamMember);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}
