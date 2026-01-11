<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class TeamWebController extends Controller
{
    /**
     * Display team details.
     */
    public function show(Team $team)
    {
        $team->load([
            'challenge',
            'leader.user',
            'members.volunteer.user',
            'members.volunteer.skills',
        ]);

        return view('teams.show', compact('team'));
    }

    /**
     * Accept team invitation.
     */
    public function accept(Team $team, Request $request)
    {
        $volunteer = $request->user()->volunteer;

        if (!$volunteer) {
            return redirect()->back()->with('error', 'You must have a volunteer profile to accept team invitations.');
        }

        $teamMember = TeamMember::where('team_id', $team->id)
            ->where('volunteer_id', $volunteer->id)
            ->first();

        if (!$teamMember) {
            return redirect()->back()->with('error', 'You are not invited to this team.');
        }

        if ($teamMember->status === 'accepted') {
            return redirect()->route('teams.show', $team)->with('info', 'You have already accepted this invitation.');
        }

        // RULE: Volunteer can only accept ONE task at a time
        // Check if volunteer already has an active task
        $activeAssignment = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->whereIn('invitation_status', ['accepted', 'in_progress', 'submitted'])
            ->first();

        if ($activeAssignment) {
            $errorMessage = 'You can only work on one task at a time. Please complete your current task before joining a new team.';
            return redirect()->back()->with('error', $errorMessage . ' Current task: "' . $activeAssignment->task->title . '"');
        }

        $teamMember->accept();

        return redirect()->route('teams.show', $team)->with('success', 'You have joined the team!');
    }

    /**
     * Decline team invitation.
     */
    public function decline(Team $team, Request $request)
    {
        $volunteer = $request->user()->volunteer;

        if (!$volunteer) {
            return redirect()->back()->with('error', 'Volunteer profile not found.');
        }

        $teamMember = TeamMember::where('team_id', $team->id)
            ->where('volunteer_id', $volunteer->id)
            ->first();

        if (!$teamMember) {
            return redirect()->back()->with('error', 'You are not invited to this team.');
        }

        $teamMember->decline();

        return redirect()->route('dashboard')->with('info', 'You have declined the team invitation.');
    }

    /**
     * Show volunteer's teams.
     */
    public function myTeams(Request $request)
    {
        $volunteer = $request->user()->volunteer;

        if (!$volunteer) {
            return redirect()->route('dashboard')->with('error', 'Volunteer profile not found.');
        }

        $teams = Team::whereHas('members', function ($query) use ($volunteer) {
            $query->where('volunteer_id', $volunteer->id);
        })
        ->with(['challenge', 'leader.user', 'members'])
        ->latest()
        ->get();

        return view('teams.my-teams', compact('teams'));
    }
}
