<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminVolunteerController extends Controller
{
    /**
     * Display all volunteers.
     */
    public function index(Request $request)
    {
        $query = Volunteer::with('user')
            ->withCount(['taskAssignments', 'certificates']);

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by field
        if ($request->has('field') && $request->field !== '') {
            $query->where('field', $request->field);
        }

        // Filter by experience level
        if ($request->has('experience_level') && $request->experience_level !== '') {
            $query->where('experience_level', $request->experience_level);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'reputation_score');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $volunteers = $query->paginate(20);

        return view('admin.volunteers.index', compact('volunteers'));
    }

    /**
     * Display a single volunteer.
     */
    public function show(Volunteer $volunteer)
    {
        $volunteer->load([
            'user',
            'skills',
            'taskAssignments.task.challenge',
            'certificates.challenge',
            'teams.challenge'
        ]);

        $stats = [
            'total_assignments' => $volunteer->taskAssignments()->count(),
            'completed_tasks' => $volunteer->taskAssignments()->whereNotNull('completed_at')->count(),
            'certificates_earned' => $volunteer->certificates()->count(),
            'reputation_score' => $volunteer->reputation_score,
        ];

        return view('admin.volunteers.show', compact('volunteer', 'stats'));
    }

    /**
     * Send email to a volunteer.
     */
    public function sendEmail(Request $request, Volunteer $volunteer)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        try {
            Mail::raw($request->message, function ($mail) use ($volunteer, $request) {
                $mail->to($volunteer->user->email, $volunteer->user->name)
                     ->subject($request->subject)
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Email sent successfully!')
                ]);
            }

            return redirect()->back()->with('success', __('Email sent successfully!'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to send email. Please try again.')
                ], 500);
            }

            return redirect()->back()->with('error', __('Failed to send email. Please try again.'));
        }
    }
}
