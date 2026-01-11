<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\WhatsAppNotification;
use App\Services\WhatsAppNotificationService;

echo "📱 WhatsApp Notification Test\n";
echo "==============================\n\n";

$phoneNumber = '+962775575110';

// Get or create a test user
$user = User::first();

if (!$user) {
    echo "❌ No users found in database\n";
    exit(1);
}

echo "👤 User: {$user->name} ({$user->email})\n";
echo "📞 Phone: {$phoneNumber}\n\n";

// Enable WhatsApp for this user
echo "1️⃣ Enabling WhatsApp notifications...\n";
$user->enableWhatsApp($phoneNumber);
echo "   ✅ WhatsApp enabled\n";
echo "   📊 Opt-in status: " . ($user->hasWhatsAppEnabled() ? 'YES' : 'NO') . "\n\n";

// Create test notifications for all 3 types
echo "2️⃣ Creating test notifications...\n\n";

// Test 1: Team Invitation
echo "   📧 Test 1: Team Invitation\n";
$team = \App\Models\Team::first();
if ($team) {
    $notification1 = WhatsAppNotificationService::queueTeamInvitation($user, $team->id);
    if ($notification1) {
        echo "   ✅ Notification #{$notification1->id} created\n";
        echo "   📋 Type: {$notification1->type}\n";
        echo "   🎯 Entity: Team #{$notification1->entity_id}\n";
        echo "   📝 Template: {$notification1->template_name}\n";
        echo "   ⏱️  Status: {$notification1->status}\n";

        // Show what message would be sent
        $challenge = $team->challenge;
        echo "\n   📨 Message Preview:\n";
        echo "   ┌─────────────────────────────────────────┐\n";
        echo "   │ Mindova Notification                    │\n";
        echo "   │                                         │\n";
        echo "   │ You have been invited to join a micro-  │\n";
        echo "   │ team for the challenge                  │\n";
        echo "   │ \"{$challenge->title}\"             │\n";
        echo "   │                                         │\n";
        echo "   │ View details:                           │\n";
        echo "   │ " . url('/teams/' . $team->id) . "     │\n";
        echo "   └─────────────────────────────────────────┘\n\n";
    } else {
        echo "   ⚠️  Notification already exists (deduplication)\n\n";
    }
} else {
    echo "   ⚠️  No teams found in database\n\n";
}

// Test 2: Task Assignment
echo "   📋 Test 2: Task Assignment\n";
$task = \App\Models\Task::first();
if ($task) {
    $notification2 = WhatsAppNotificationService::queueTaskAssignment($user, $task->id);
    if ($notification2) {
        echo "   ✅ Notification #{$notification2->id} created\n";
        echo "   📋 Type: {$notification2->type}\n";
        echo "   🎯 Entity: Task #{$notification2->entity_id}\n";
        echo "   📝 Template: {$notification2->template_name}\n";
        echo "   ⏱️  Status: {$notification2->status}\n";

        echo "\n   📨 Message Preview:\n";
        echo "   ┌─────────────────────────────────────────┐\n";
        echo "   │ Mindova Notification                    │\n";
        echo "   │                                         │\n";
        echo "   │ A new task has been assigned to you:    │\n";
        echo "   │ \"{$task->title}\"                  │\n";
        echo "   │                                         │\n";
        echo "   │ View details:                           │\n";
        echo "   │ " . url('/tasks/' . $task->id) . "      │\n";
        echo "   └─────────────────────────────────────────┘\n\n";
    } else {
        echo "   ⚠️  Notification already exists (deduplication)\n\n";
    }
} else {
    echo "   ⚠️  No tasks found in database\n\n";
}

// Test 3: Critical Update
echo "   🚨 Test 3: Critical Update\n";
$challenge = \App\Models\Challenge::first();
if ($challenge) {
    $notification3 = WhatsAppNotificationService::queueCriticalUpdate($user, $challenge->id);
    if ($notification3) {
        echo "   ✅ Notification #{$notification3->id} created\n";
        echo "   📋 Type: {$notification3->type}\n";
        echo "   🎯 Entity: Challenge #{$notification3->entity_id}\n";
        echo "   📝 Template: {$notification3->template_name}\n";
        echo "   ⏱️  Status: {$notification3->status}\n";

        echo "\n   📨 Message Preview:\n";
        echo "   ┌─────────────────────────────────────────┐\n";
        echo "   │ Mindova Notification                    │\n";
        echo "   │                                         │\n";
        echo "   │ Critical update on challenge            │\n";
        echo "   │ \"{$challenge->title}\":            │\n";
        echo "   │ Please check the latest update.         │\n";
        echo "   │                                         │\n";
        echo "   │ View details:                           │\n";
        echo "   │ " . url('/challenges/' . $challenge->id) . " │\n";
        echo "   └─────────────────────────────────────────┘\n\n";
    } else {
        echo "   ⚠️  Notification already exists (deduplication)\n\n";
    }
} else {
    echo "   ⚠️  No challenges found in database\n\n";
}

// Show summary
echo "3️⃣ Summary\n";
echo "   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$stats = WhatsAppNotificationService::getStats($user);
echo "   📊 Total notifications: {$stats['total']}\n";
echo "   ⏳ Queued: {$stats['queued']}\n";
echo "   ✅ Sent: {$stats['sent']}\n";
echo "   ❌ Failed: {$stats['failed']}\n\n";

// Show queued jobs
$queuedNotifications = WhatsAppNotification::where('user_id', $user->id)
    ->where('status', 'queued')
    ->get();

if ($queuedNotifications->count() > 0) {
    echo "4️⃣ Queued Notifications\n";
    echo "   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($queuedNotifications as $n) {
        echo "   #{$n->id}: {$n->type} → {$n->entity_type} #{$n->entity_id}\n";
    }
    echo "\n";
}

echo "⚠️  IMPORTANT NOTES:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "1. Notifications are QUEUED, not sent yet\n";
echo "2. They will be sent with 1-2 minute delay\n";
echo "3. To actually send, you need:\n";
echo "   - Configure Twilio credentials in .env\n";
echo "   - Install: composer require twilio/sdk\n";
echo "   - Run: php artisan queue:work\n\n";

echo "4. Current Twilio config status:\n";
$hasSid = !empty(config('services.twilio.account_sid'));
$hasToken = !empty(config('services.twilio.auth_token'));
$hasFrom = !empty(config('services.twilio.whatsapp_from'));

echo "   - Account SID: " . ($hasSid ? '✅ Set' : '❌ Not set') . "\n";
echo "   - Auth Token: " . ($hasToken ? '✅ Set' : '❌ Not set') . "\n";
echo "   - WhatsApp From: " . ($hasFrom ? '✅ Set' : '❌ Not set') . "\n\n";

if (!$hasSid || !$hasToken || !$hasFrom) {
    echo "⚙️  To enable sending, update .env with:\n";
    echo "   TWILIO_ACCOUNT_SID=your_account_sid\n";
    echo "   TWILIO_AUTH_TOKEN=your_auth_token\n";
    echo "   TWILIO_WHATSAPP_FROM=+14155238886\n\n";
}

echo "✅ Test complete!\n";
