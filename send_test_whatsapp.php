<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\WhatsAppNotificationService;

echo "📱 Creating WhatsApp Test Notification\n";
echo "=====================================\n\n";

$phoneNumber = '+962792903538';

// Get first user
$user = User::first();

if (!$user) {
    echo "❌ No users found\n";
    exit(1);
}

echo "👤 User: {$user->name}\n";
echo "📞 Phone: {$phoneNumber}\n\n";

// Enable WhatsApp
echo "1️⃣ Enabling WhatsApp for user...\n";
$user->update([
    'whatsapp_opt_in' => true,
    'whatsapp_number' => $phoneNumber,
    'whatsapp_opted_in_at' => now(),
    'whatsapp_opted_out_at' => null,
]);
echo "   ✅ WhatsApp enabled\n\n";

// Get first task
$task = \App\Models\Task::first();

if (!$task) {
    echo "❌ No tasks found\n";
    exit(1);
}

echo "2️⃣ Creating task assignment notification...\n";
echo "   📋 Task: {$task->title}\n";

$notification = WhatsAppNotificationService::queueTaskAssignment($user, $task->id);

if ($notification) {
    echo "   ✅ Notification created!\n";
    echo "   📝 ID: #{$notification->id}\n";
    echo "   🎯 Type: {$notification->type}\n";
    echo "   ⏱️  Status: {$notification->status}\n\n";

    echo "3️⃣ Checking queue...\n";
    $jobCount = DB::table('jobs')->count();
    echo "   📊 Jobs in queue: {$jobCount}\n\n";

    echo "✅ Ready to send!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Next step: php artisan queue:work\n";
} else {
    echo "❌ Failed to create notification\n";
    echo "   This may be due to deduplication\n";
    exit(1);
}
