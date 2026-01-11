<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use App\Mail\BugReportSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class BugReportController extends Controller
{
    /**
     * Store a bug report.
     * Minimal validation - capture friction signals only.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'issue_type' => 'required|in:bug,ui_ux_issue,confusing_flow,something_didnt_work',
            'description' => 'required|string|max:1000',
            'current_page' => 'required|string|max:255',
            'blocked_user' => 'nullable|boolean',
            'screenshot' => 'nullable|image|max:5120', // Max 5MB
        ]);

        // Handle screenshot upload if provided
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('bug_reports', 'public');
        }

        // Create bug report
        $bugReport = BugReport::create([
            'user_id' => auth()->id(), // Null if not logged in
            'issue_type' => $validated['issue_type'],
            'description' => $validated['description'],
            'current_page' => $validated['current_page'],
            'screenshot' => $screenshotPath,
            'blocked_user' => $validated['blocked_user'] ?? false,
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        // Send email notification to Mindova owner
        $ownerEmail = config('mail.owner_email', 'owner@mindova.com');
        Mail::to($ownerEmail)->send(new BugReportSubmitted($bugReport));

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your report. This helps us improve the platform.',
        ]);
    }
}
