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

// Language Switcher Routes
Route::post('/language/switch', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language/current', [App\Http\Controllers\LanguageController::class, 'current'])->name('language.current');

// Static Pages
Route::get('/how-it-works', function () { return view('pages.how-it-works'); })->name('how-it-works');
Route::get('/success-stories', function () { return view('pages.success-stories'); })->name('success-stories');
Route::get('/help', function () { return view('pages.help'); })->name('help');
Route::get('/guidelines', function () { return view('pages.guidelines'); })->name('guidelines');
Route::get('/api-docs', function () { return view('pages.api-docs'); })->name('api-docs');
Route::get('/blog', function () { return view('pages.blog'); })->name('blog');
Route::get('/about', function () { return view('pages.about'); })->name('about');
Route::get('/contact', function () { return view('pages.contact'); })->name('contact');
Route::get('/privacy', function () { return view('pages.privacy'); })->name('privacy');
Route::get('/terms', function () { return view('pages.terms'); })->name('terms');

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
