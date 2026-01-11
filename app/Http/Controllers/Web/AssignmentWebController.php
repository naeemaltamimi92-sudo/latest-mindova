<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TaskAssignment;
use App\Models\WorkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentWebController extends Controller
{
    /**
     * Show volunteer's assignments grouped by status.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isVolunteer() || !$user->volunteer) {
            return redirect()->route('dashboard')
                ->with('error', 'Only volunteers can view assignments.');
        }

        $volunteer = $user->volunteer;

        // Fetch all assignments grouped by status
        $assignments = TaskAssignment::where('volunteer_id', $volunteer->id)
            ->with(['task.challenge', 'task.workstream'])
            ->get()
            ->groupBy('invitation_status');

        return view('assignments.index', compact('assignments'));
    }
}
