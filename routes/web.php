<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ChallengeWebController;
use App\Http\Controllers\Web\TaskWebController;
use App\Http\Controllers\Web\AssignmentWebController;
use App\Http\Controllers\Web\VolunteerWebController;
use App\Http\Controllers\Web\CompanyWebController;
use App\Http\Controllers\Web\LeaderboardController;
use App\Http\Controllers\NdaController;

// Home
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// Maintenance Page (accessible during maintenance mode)
Route::get('/maintenance', function () {
    if (!\App\Models\SiteSetting::isMaintenanceMode()) {
        return redirect()->route('home');
    }
    return view('maintenance');
})->name('maintenance')->withoutMiddleware([\App\Http\Middleware\CheckMaintenanceMode::class]);

// Static Pages
Route::get('/how-it-works', function () { return view('pages.how-it-works'); })->name('how-it-works');
Route::get('/success-stories', [App\Http\Controllers\Web\SuccessStoryController::class, 'index'])->name('success-stories');
Route::get('/success-stories/{slug}', [App\Http\Controllers\Web\SuccessStoryController::class, 'show'])->name('success-stories.show');
Route::get('/help', function () { return view('pages.help'); })->name('help');
Route::get('/guidelines', function () { return view('pages.guidelines'); })->name('guidelines');
Route::get('/api-docs', function () { return view('pages.api-docs'); })->name('api-docs');
Route::get('/blog', function () { return view('pages.blog'); })->name('blog');
Route::get('/about', function () { return view('pages.about'); })->name('about');
Route::get('/contact', function () { return view('pages.contact'); })->name('contact');
Route::get('/privacy', function () { return view('pages.privacy'); })->name('privacy');
Route::get('/terms', function () { return view('pages.terms'); })->name('terms');

// Static Pages - Footer (Product / Company / Resources)
Route::get('/features', function () { return view('pages.features'); })->name('features');
Route::get('/pricing', function () { return view('pages.pricing'); })->name('pricing');
Route::get('/security', function () { return view('pages.security'); })->name('security');
Route::get('/integrations', function () { return view('pages.integrations'); })->name('integrations');
Route::get('/changelog', function () { return view('pages.changelog'); })->name('changelog');
Route::get('/careers', function () { return view('pages.careers'); })->name('careers');
Route::get('/press', function () { return view('pages.press'); })->name('press');
Route::get('/partners', function () { return view('pages.partners'); })->name('partners');
Route::get('/documentation', function () { return view('pages.documentation'); })->name('documentation');

// Authentication
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/auth/linkedin/redirect', [App\Http\Controllers\Auth\LinkedInAuthController::class, 'redirect'])->name('auth.linkedin.redirect');
Route::get('/auth/linkedin/callback', [App\Http\Controllers\Auth\LinkedInAuthController::class, 'callback'])->name('auth.linkedin.callback');

Route::get('/complete-profile', function () {
    return view('auth.complete-profile');
})->name('complete-profile')->middleware('auth');

Route::post('/complete-profile/volunteer', [App\Http\Controllers\Web\ProfileController::class, 'completeVolunteerProfile'])->name('complete-profile.volunteer')->middleware('auth');
Route::post('/complete-profile/company', [App\Http\Controllers\Web\ProfileController::class, 'completeCompanyProfile'])->name('complete-profile.company')->middleware('auth');

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Two-Factor Challenge (post-password, pre-verification)
Route::get('/2fa/challenge', function () {
    return view('auth.two-factor-challenge');
})->name('two-factor.challenge')->middleware('auth');

// Password Reset
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');

// NDA Routes
Route::middleware('auth')->group(function () {
    // General NDA (for all volunteers)
    Route::get('/nda/general', [NdaController::class, 'showGeneralNda'])->name('nda.general');
    Route::post('/nda/general/sign', [NdaController::class, 'signGeneralNda'])->name('nda.general.sign');

    // Challenge-specific NDA
    Route::get('/nda/challenge/{challenge}', [NdaController::class, 'showChallengeNda'])->name('nda.challenge');
    Route::post('/nda/challenge/{challenge}/sign', [NdaController::class, 'signChallengeNda'])->name('nda.challenge.sign');

    // NDA status check (AJAX)
    Route::get('/nda/challenge/{challenge}/status', [NdaController::class, 'checkNdaStatus'])->name('nda.challenge.status');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Challenges
    Route::get('/challenges', [ChallengeWebController::class, 'index'])->name('challenges.index');
    Route::get('/challenges/create', [ChallengeWebController::class, 'create'])->name('challenges.create');
    Route::post('/challenges', [ChallengeWebController::class, 'store'])->name('challenges.store');
    // IMPORTANT: Edit route MUST come before show route to avoid {challenge} capturing "id/edit"
    Route::get('/challenges/{challenge}/edit', [ChallengeWebController::class, 'edit'])->name('challenges.edit');
    Route::put('/challenges/{challenge}', [ChallengeWebController::class, 'update'])->name('challenges.update');
    Route::delete('/challenges/{challenge}', [ChallengeWebController::class, 'destroy'])->name('challenges.destroy');
    Route::get('/challenges/{challenge}', [ChallengeWebController::class, 'show'])->name('challenges.show')->middleware('nda.challenge');

    // Community Challenges
    Route::get('/community', [App\Http\Controllers\Web\CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/{challenge}', [App\Http\Controllers\Web\CommunityController::class, 'show'])->name('community.challenge');
    Route::post('/community/{challenge}/comment', [App\Http\Controllers\Web\CommunityController::class, 'storeComment'])->name('community.comment');
    Route::post('/community/comment/{comment}/vote', [App\Http\Controllers\Web\CommunityController::class, 'voteComment'])->name('community.comment.vote');
    Route::post('/community/idea/{idea}/vote', [App\Http\Controllers\Web\CommunityController::class, 'voteIdea'])->name('community.idea.vote');
    Route::post('/community/{challenge}/ideas/{idea}/mark-correct', [App\Http\Controllers\Web\CommunityController::class, 'markCorrectAnswer'])->name('community.idea.mark-correct');

    // Volunteer Community Challenges (submit challenges to community)
    Route::post('/community/challenges', [App\Http\Controllers\Web\VolunteerChallengeController::class, 'store'])->name('volunteer.challenges.store');
    Route::get('/my-challenges', [App\Http\Controllers\Web\VolunteerChallengeController::class, 'index'])->name('volunteer.challenges.index');
    Route::get('/my-challenges/{challenge}', [App\Http\Controllers\Web\VolunteerChallengeController::class, 'show'])->name('volunteer.challenges.show');
    Route::get('/my-challenges/{challenge}/status', [App\Http\Controllers\Web\VolunteerChallengeController::class, 'status'])->name('volunteer.challenges.status');
    Route::put('/my-challenges/{challenge}', [App\Http\Controllers\Web\VolunteerChallengeController::class, 'update'])->name('volunteer.challenges.update');
    Route::delete('/my-challenges/{challenge}', [App\Http\Controllers\Web\VolunteerChallengeController::class, 'destroy'])->name('volunteer.challenges.destroy');

    // Profile
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    Route::post('/profile/volunteer/update', [App\Http\Controllers\Web\ProfileController::class, 'updateVolunteerProfile'])->name('profile.volunteer.update');
    Route::post('/profile/company/update', [App\Http\Controllers\Web\ProfileController::class, 'updateCompanyProfile'])->name('profile.company.update');

    // LinkedIn account management (authenticated users)
    Route::get('/profile/linkedin/connect', [App\Http\Controllers\Auth\LinkedInAuthController::class, 'connect'])->name('linkedin.connect');
    Route::post('/profile/linkedin/disconnect', [App\Http\Controllers\Auth\LinkedInAuthController::class, 'disconnect'])->name('linkedin.disconnect');
    Route::post('/profile/linkedin/url', [App\Http\Controllers\Auth\LinkedInAuthController::class, 'updateLinkedInUrl'])->name('linkedin.url.update');

    // Security Settings
    Route::prefix('security')->name('security.')->group(function () {
        Route::post('/password/change', [App\Http\Controllers\Web\SecurityController::class, 'changePassword'])->name('password.change');
        Route::post('/2fa/enable', [App\Http\Controllers\Web\SecurityController::class, 'enableTwoFactor'])->name('2fa.enable');
        Route::post('/2fa/confirm', [App\Http\Controllers\Web\SecurityController::class, 'confirmTwoFactor'])->name('2fa.confirm');
        Route::post('/2fa/disable', [App\Http\Controllers\Web\SecurityController::class, 'disableTwoFactor'])->name('2fa.disable');
        Route::post('/2fa/verify', [App\Http\Controllers\Web\SecurityController::class, 'verifyTwoFactor'])->name('2fa.verify');
        Route::post('/2fa/recovery-codes', [App\Http\Controllers\Web\SecurityController::class, 'regenerateRecoveryCodes'])->name('2fa.recovery-codes');
        Route::get('/2fa/status', [App\Http\Controllers\Web\SecurityController::class, 'getTwoFactorStatus'])->name('2fa.status');
    });

    // Bug Reports (Available to all authenticated users)
    Route::post('/bug-reports', [App\Http\Controllers\Web\BugReportController::class, 'store'])->name('bug-reports.store');

    // Contextual Guide API
    Route::post('/api/contextual-guide/dismiss', [App\Http\Controllers\ContextualGuideController::class, 'dismiss'])->name('contextual-guide.dismiss');
    Route::post('/api/contextual-guide/reset', [App\Http\Controllers\ContextualGuideController::class, 'reset'])->name('contextual-guide.reset');
    Route::get('/api/contextual-guide/status', [App\Http\Controllers\ContextualGuideController::class, 'checkStatus'])->name('contextual-guide.status');

    // Guided Tour / User Guidance Routes
    Route::post('/api/guidance/complete', [App\Http\Controllers\GuidanceController::class, 'completeStep'])->name('guidance.complete');
    Route::get('/api/guidance/progress', [App\Http\Controllers\GuidanceController::class, 'getProgress'])->name('guidance.progress');
    Route::post('/api/guidance/reset', [App\Http\Controllers\GuidanceController::class, 'resetProgress'])->name('guidance.reset');

    // Challenge Attachments Routes
    Route::post('/challenges/{challenge}/attachments', [App\Http\Controllers\ChallengeAttachmentController::class, 'upload'])->name('challenges.attachments.upload');
    Route::get('/challenges/{challenge}/attachments', [App\Http\Controllers\ChallengeAttachmentController::class, 'index'])->name('challenges.attachments.index');
    Route::delete('/challenges/{challenge}/attachments/{attachment}', [App\Http\Controllers\ChallengeAttachmentController::class, 'destroy'])->name('challenges.attachments.destroy');
    Route::get('/challenges/{challenge}/attachments/{attachment}/download', [App\Http\Controllers\ChallengeAttachmentController::class, 'download'])->name('challenges.attachments.download');

    // Certificate Routes (Company)
    Route::get('/challenges/{challenge}/confirm-completion', [App\Http\Controllers\CertificateController::class, 'showConfirmationForm'])->name('challenges.confirm');
    Route::post('/challenges/{challenge}/issue-certificates', [App\Http\Controllers\CertificateController::class, 'submitConfirmation'])->name('challenges.issue-certificates');

    // Certificate Routes (Volunteers & Public)
    Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/verify', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');
    Route::get('/certificates/{certificate}', [App\Http\Controllers\CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');
    Route::post('/certificates/{certificate}/revoke', [App\Http\Controllers\CertificateController::class, 'revoke'])->name('certificates.revoke');
    Route::post('/certificates/{certificate}/regenerate', [App\Http\Controllers\CertificateController::class, 'regenerate'])->name('certificates.regenerate');

    // Tasks (Volunteers) - Requires general NDA
    Route::middleware('nda.general')->group(function () {
        Route::get('/tasks/available', [TaskWebController::class, 'available'])->name('tasks.available');
        Route::get('/tasks/{task}', [TaskWebController::class, 'show'])->name('tasks.show');
    });

    // Assignments (Volunteers) - Requires general NDA
    Route::middleware('nda.general')->group(function () {
        Route::get('/assignments', [AssignmentWebController::class, 'index'])->name('assignments.my');
        Route::post('/assignments/{assignment}/accept', [App\Http\Controllers\Task\TaskAssignmentController::class, 'accept'])->name('assignments.accept');
        Route::post('/assignments/{assignment}/decline', [App\Http\Controllers\Task\TaskAssignmentController::class, 'reject'])->name('assignments.decline');
        Route::post('/assignments/{assignment}/start', [App\Http\Controllers\Task\TaskAssignmentController::class, 'start'])->name('assignments.start');
        Route::post('/assignments/{assignment}/submit-solution', [App\Http\Controllers\Task\TaskAssignmentController::class, 'submitSolution'])->name('assignments.submit-solution');
    });

    // Ideas
    Route::get('/ideas/{idea}', function (\App\Models\Idea $idea) {
        $idea->load([
            'volunteer.user',
            'challenge.company',
            'challenge.ideas.volunteer.user'
        ]);
        return view('ideas.show', compact('idea'));
    })->name('ideas.show');

    Route::get('/challenges/{challenge}/ideas/create', function (\App\Models\Challenge $challenge) {
        return view('ideas.create', compact('challenge'));
    })->name('ideas.create');

    // Public Profiles
    Route::get('/volunteers/{id}', [VolunteerWebController::class, 'show'])->name('volunteers.show');
    Route::get('/companies/{id}', [CompanyWebController::class, 'show'])->name('companies.show');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');


    // Expert System (500+ Stars required)
    Route::prefix('expert')->name('expert.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Web\ExpertController::class, 'dashboard'])->name('dashboard');
        Route::get('/challenges/{challenge}', [App\Http\Controllers\Web\ExpertController::class, 'showChallenge'])->name('challenge');
        Route::post('/assignments/{assignment}/accept', [App\Http\Controllers\Web\ExpertController::class, 'acceptInvitation'])->name('accept');
        Route::post('/assignments/{assignment}/decline', [App\Http\Controllers\Web\ExpertController::class, 'declineInvitation'])->name('decline');
        Route::post('/certificates/{certificate}/approve', [App\Http\Controllers\Web\ExpertController::class, 'approveCertificate'])->name('certificate.approve');
    });


    // =========================================================================
    // Verified Talent Marketplace
    // =========================================================================
    Route::prefix('talent')->name('talent.')->group(function () {
        Route::get('/', [App\Http\Controllers\Web\TalentMarketplaceController::class, 'index'])->name('index');
        Route::get('/verify-hire', [App\Http\Controllers\Web\TalentMarketplaceController::class, 'verifyHire'])->name('verify-hire');
        Route::get('/{volunteer}', [App\Http\Controllers\Web\TalentMarketplaceController::class, 'profile'])->name('profile');
        Route::get('/{volunteer}/hire', [App\Http\Controllers\Web\HireRequestController::class, 'create'])->name('hire');
        Route::post('/{volunteer}/hire', [App\Http\Controllers\Web\HireRequestController::class, 'store'])->name('hire.store');
    });

    // Hire Requests (manage inbox)
    Route::prefix('hire-requests')->name('hire-requests.')->group(function () {
        Route::get('/', [App\Http\Controllers\Web\HireRequestController::class, 'index'])->name('index');
        Route::post('/{hireRequest}/accept', [App\Http\Controllers\Web\HireRequestController::class, 'accept'])->name('accept');
        Route::post('/{hireRequest}/decline', [App\Http\Controllers\Web\HireRequestController::class, 'decline'])->name('decline');
    });

    // Recruitment Agency White-Label Portal
    Route::prefix('agency')->name('agency.')->group(function () {
        Route::get('/setup', [App\Http\Controllers\Web\AgencyPortalController::class, 'setup'])->name('setup');
        Route::post('/setup', [App\Http\Controllers\Web\AgencyPortalController::class, 'store'])->name('store');
        Route::get('/dashboard', [App\Http\Controllers\Web\AgencyPortalController::class, 'dashboard'])->name('dashboard');
    });

    // Settings
    Route::get('/settings/notifications', function () {
        return view('settings.notifications');
    })->name('settings.notifications');

    // Challenge Analytics (Company only)
    Route::get('/challenges/{challenge}/analytics', [ChallengeWebController::class, 'analytics'])->name('challenges.analytics');

    // Teams
    Route::get('/teams/{team}', [App\Http\Controllers\Web\TeamWebController::class, 'show'])->name('teams.show');
    Route::post('/teams/{team}/accept', [App\Http\Controllers\Web\TeamWebController::class, 'accept'])->name('teams.accept');
    Route::post('/teams/{team}/decline', [App\Http\Controllers\Web\TeamWebController::class, 'decline'])->name('teams.decline');
    Route::get('/my-teams', [App\Http\Controllers\Web\TeamWebController::class, 'myTeams'])->name('teams.my');

    // Company Submissions (for reviewing volunteer work)
    Route::get('/company/submissions', [CompanyWebController::class, 'submissions'])->name('company.submissions.index');
    Route::get('/company/submissions/{submission}', [CompanyWebController::class, 'showSubmission'])->name('company.submissions.show');
    Route::post('/company/submissions/{submission}/review', [CompanyWebController::class, 'reviewSubmission'])->name('company.submissions.review');
});

// Admin Routes (Mindova Owner/Manager)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
        ->name('dashboard');
    Route::post('/notifications/mark-read', [App\Http\Controllers\Admin\AdminDashboardController::class, 'markNotificationsRead'])
        ->name('notifications.markRead');

    // Challenges Management - View and Delete Only
    Route::get('/challenges', [App\Http\Controllers\Admin\AdminChallengeController::class, 'index'])
        ->name('challenges.index');
    Route::get('/challenges/analytics', [App\Http\Controllers\Admin\AdminChallengeController::class, 'analytics'])
        ->name('challenges.analytics');
    Route::get('/challenges/export', [App\Http\Controllers\Admin\AdminChallengeController::class, 'export'])
        ->name('challenges.export');
    Route::post('/challenges/bulk-delete', [App\Http\Controllers\Admin\AdminChallengeController::class, 'bulkDelete'])
        ->name('challenges.bulkDelete');
    Route::get('/challenges/{challenge}', [App\Http\Controllers\Admin\AdminChallengeController::class, 'show'])
        ->name('challenges.show');
    Route::delete('/challenges/{challenge}', [App\Http\Controllers\Admin\AdminChallengeController::class, 'destroy'])
        ->name('challenges.destroy');

    // Trigger expert assignment for a challenge (admin action)
    Route::post('/challenges/{challenge}/assign-experts', [App\Http\Controllers\Web\ExpertController::class, 'assignExperts'])
        ->name('challenges.assign-experts');

    // Companies Management
    Route::get('/companies', [App\Http\Controllers\Admin\AdminCompanyController::class, 'index'])
        ->name('companies.index');
    Route::get('/companies/{company}', [App\Http\Controllers\Admin\AdminCompanyController::class, 'show'])
        ->name('companies.show');

    // Volunteers Management
    Route::get('/volunteers', [App\Http\Controllers\Admin\AdminVolunteerController::class, 'index'])
        ->name('volunteers.index');
    Route::get('/volunteers/{volunteer}', [App\Http\Controllers\Admin\AdminVolunteerController::class, 'show'])
        ->name('volunteers.show');
    Route::post('/volunteers/{volunteer}/send-email', [App\Http\Controllers\Admin\AdminVolunteerController::class, 'sendEmail'])
        ->name('volunteers.send-email');

    // Platform Settings
    Route::get('/settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])
        ->name('settings.update');
    Route::post('/settings/toggle', [App\Http\Controllers\Admin\AdminSettingsController::class, 'toggle'])
        ->name('settings.toggle');
    Route::post('/settings/update-single', [App\Http\Controllers\Admin\AdminSettingsController::class, 'updateSingle'])
        ->name('settings.updateSingle');
    Route::get('/settings/export', [App\Http\Controllers\Admin\AdminSettingsController::class, 'export'])
        ->name('settings.export');
    Route::post('/settings/reset-group', [App\Http\Controllers\Admin\AdminSettingsController::class, 'resetGroup'])
        ->name('settings.resetGroup');
    Route::post('/settings/apply-preset', [App\Http\Controllers\Admin\AdminSettingsController::class, 'applyPreset'])
        ->name('settings.applyPreset');
    Route::post('/settings/import', [App\Http\Controllers\Admin\AdminSettingsController::class, 'import'])
        ->name('settings.import');
    Route::post('/settings/clear-cache', [App\Http\Controllers\Admin\AdminSettingsController::class, 'clearCache'])
        ->name('settings.clearCache');
    Route::get('/settings/search', [App\Http\Controllers\Admin\AdminSettingsController::class, 'search'])
        ->name('settings.search');
    Route::post('/settings/bulk-update', [App\Http\Controllers\Admin\AdminSettingsController::class, 'bulkUpdate'])
        ->name('settings.bulkUpdate');
});

// Public Agency White-Label Portal — intentionally no auth; external visitors browse company talent portals
Route::get('/agency/portal/{slug}', [App\Http\Controllers\Web\AgencyPortalController::class, 'publicPortal'])->name('agency.portal');

// ============================================================================
// MINDOVA INTERNAL ORGANIZATION ROUTES
// ============================================================================

// Setup route (only accessible if no owner exists)
Route::get('/mindova-admin/setup', [App\Http\Controllers\Mindova\SetupController::class, 'showSetupForm'])
    ->name('mindova.setup');
Route::post('/mindova-admin/setup', [App\Http\Controllers\Mindova\SetupController::class, 'setup'])
    ->name('mindova.setup.submit');

// Auth routes (public)
Route::get('/mindova-admin/login', [App\Http\Controllers\Mindova\AuthController::class, 'showLoginForm'])
    ->name('mindova.login');
Route::post('/mindova-admin/login', [App\Http\Controllers\Mindova\AuthController::class, 'login'])
    ->name('mindova.login.submit');

// Protected Mindova routes
Route::prefix('mindova-admin')->name('mindova.')->middleware(['mindova.auth'])->group(function () {
    // Logout
    Route::post('/logout', [App\Http\Controllers\Mindova\AuthController::class, 'logout'])
        ->name('logout');

    // Password change
    Route::get('/password/change', [App\Http\Controllers\Mindova\AuthController::class, 'showPasswordChangeForm'])
        ->name('password.change');
    Route::post('/password/change', [App\Http\Controllers\Mindova\AuthController::class, 'changePassword'])
        ->name('password.change.submit');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Mindova\DashboardController::class, 'index'])
        ->name('dashboard');

    // Team Management (Owner only can create/edit/delete)
    Route::middleware(['mindova.permission:team.view'])->group(function () {
        Route::get('/team', [App\Http\Controllers\Mindova\TeamController::class, 'index'])
            ->name('team.index');
    });

    Route::middleware(['mindova.owner'])->group(function () {
        Route::get('/team/create', [App\Http\Controllers\Mindova\TeamController::class, 'create'])
            ->name('team.create');
        Route::post('/team', [App\Http\Controllers\Mindova\TeamController::class, 'store'])
            ->name('team.store');
        Route::get('/team/{member}/edit', [App\Http\Controllers\Mindova\TeamController::class, 'edit'])
            ->name('team.edit');
        Route::put('/team/{member}', [App\Http\Controllers\Mindova\TeamController::class, 'update'])
            ->name('team.update');
        Route::delete('/team/{member}', [App\Http\Controllers\Mindova\TeamController::class, 'destroy'])
            ->name('team.destroy');
        Route::post('/team/{member}/deactivate', [App\Http\Controllers\Mindova\TeamController::class, 'deactivate'])
            ->name('team.deactivate');
        Route::post('/team/{member}/activate', [App\Http\Controllers\Mindova\TeamController::class, 'activate'])
            ->name('team.activate');
        Route::post('/team/{member}/resend-invitation', [App\Http\Controllers\Mindova\TeamController::class, 'resendInvitation'])
            ->name('team.resend-invitation');
    });

    // Audit Logs
    Route::middleware(['mindova.permission:audit.view'])->group(function () {
        Route::get('/audit', [App\Http\Controllers\Mindova\AuditLogController::class, 'index'])
            ->name('audit.index');
    });

    Route::middleware(['mindova.permission:audit.export'])->group(function () {
        Route::get('/audit/export', [App\Http\Controllers\Mindova\AuditLogController::class, 'export'])
            ->name('audit.export');
    });
});
