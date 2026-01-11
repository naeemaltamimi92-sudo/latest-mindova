<?php

use App\Http\Controllers\Auth\LinkedInAuthController;
use App\Http\Controllers\Volunteer\VolunteerController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Challenge\ChallengeController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskAssignmentController;
use App\Http\Controllers\Idea\IdeaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\TeamMessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::get('linkedin/redirect', [LinkedInAuthController::class, 'redirect']);
    Route::get('linkedin/callback', [LinkedInAuthController::class, 'callback']);
    Route::post('logout', [LinkedInAuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {

    // Volunteer Routes
    Route::prefix('volunteers')->group(function () {
        Route::post('complete-profile', [VolunteerController::class, 'completeProfile']);
        Route::get('profile', [VolunteerController::class, 'showProfile']);
        Route::put('profile', [VolunteerController::class, 'updateProfile']);
        Route::post('upload-cv', [VolunteerController::class, 'uploadCV']);
    });

    // Company Routes
    Route::prefix('companies')->group(function () {
        Route::post('complete-profile', [CompanyController::class, 'completeProfile']);
        Route::get('profile', [CompanyController::class, 'showProfile']);
        Route::put('profile', [CompanyController::class, 'updateProfile']);
    });

    // Challenge Routes
    Route::prefix('challenges')->group(function () {
        Route::get('/', [ChallengeController::class, 'index']);
        Route::post('/', [ChallengeController::class, 'store']);
        Route::get('/my-challenges', [ChallengeController::class, 'myChallenges']);
        Route::get('/{challenge}', [ChallengeController::class, 'show']);
        Route::put('/{challenge}', [ChallengeController::class, 'update']);
        Route::post('/{challenge}/archive', [ChallengeController::class, 'archive']);
    });

    // Task Routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::get('/available', [TaskController::class, 'availableForVolunteer']);
        Route::get('/my-tasks', [TaskController::class, 'myTasks']);
        Route::get('/{task}', [TaskController::class, 'show']);
    });

    // Task Assignment Routes
    Route::prefix('assignments')->group(function () {
        Route::get('/', [TaskAssignmentController::class, 'index']);
        Route::get('/pending', [TaskAssignmentController::class, 'pending']);
        Route::post('/{assignment}/accept', [TaskAssignmentController::class, 'accept']);
        Route::post('/{assignment}/reject', [TaskAssignmentController::class, 'reject']);
        Route::post('/{assignment}/start', [TaskAssignmentController::class, 'start']);
        Route::post('/{assignment}/complete', [TaskAssignmentController::class, 'complete']);
        Route::post('/{assignment}/submit-solution', [TaskAssignmentController::class, 'submitSolution']);
    });

    // Idea Routes (Community Discussion)
    Route::prefix('ideas')->group(function () {
        Route::get('/my-ideas', [IdeaController::class, 'myIdeas']);
        Route::get('/{idea}', [IdeaController::class, 'show']);
        Route::post('/{idea}/vote', [IdeaController::class, 'vote']);
    });

    Route::prefix('challenges/{challenge}/ideas')->group(function () {
        Route::get('/', [IdeaController::class, 'index']);
        Route::post('/', [IdeaController::class, 'store']);
    });

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
    });

    // Team Message Routes
    Route::prefix('teams/{team}/messages')->group(function () {
        Route::get('/', [TeamMessageController::class, 'index']);
        Route::post('/', [TeamMessageController::class, 'store']);
    });

    // User Routes
    Route::get('user', function () {
        return response()->json([
            'user' => auth()->user()->load(['volunteer', 'company']),
        ]);
    });
});

// Contextual Assistant Routes (no auth required)
Route::prefix('contextual-assistant')->group(function () {
    Route::post('dismiss', [\App\Http\Controllers\Api\ContextualAssistantController::class, 'dismiss']);
    Route::post('enable', [\App\Http\Controllers\Api\ContextualAssistantController::class, 'enable']);
});

// In-App Guide Routes (no auth required)
Route::prefix('in-app-guide')->group(function () {
    Route::get('{pageIdentifier}', [\App\Http\Controllers\Api\InAppGuideController::class, 'show']);
    Route::post('dismiss', [\App\Http\Controllers\Api\InAppGuideController::class, 'dismiss']);
    Route::post('reset', [\App\Http\Controllers\Api\InAppGuideController::class, 'reset']);
});

/*
|--------------------------------------------------------------------------
| WhatsApp Webhook Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming webhooks from Meta WhatsApp Business API.
| The GET route is for webhook verification, POST is for receiving messages.
|
*/
Route::prefix('whatsapp')->group(function () {
    // Webhook verification (GET) - Called by Meta to verify the webhook URL
    Route::get('webhook', [\App\Http\Controllers\Api\WhatsAppWebhookController::class, 'verify'])
        ->name('whatsapp.webhook.verify');

    // Webhook handler (POST) - Receives messages and status updates
    Route::post('webhook', [\App\Http\Controllers\Api\WhatsAppWebhookController::class, 'handle'])
        ->name('whatsapp.webhook.handle');

    // Status endpoint (for debugging) - Protected
    Route::get('status', [\App\Http\Controllers\Api\WhatsAppWebhookController::class, 'status'])
        ->middleware('auth:sanctum')
        ->name('whatsapp.status');
});
