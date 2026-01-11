<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\WhatsAppNotificationService;

echo "📱 Sending Critical Update to +962792903538\n";
echo "==========================================\n\n";

$phoneNumber = '+962792903538';

// Get first user
$user = User::first();

if (!$user) {
    echo "❌ No users found\n";
    exit(1);
}

echo "👤 User: {$user->name}\n";
echo "📞 Phone: {$phoneNumber}\n\n";

// Update WhatsApp number back to this one
$user->update([
    'whatsapp_opt_in' => true,
    'whatsapp_number' => $phoneNumber,
    'whatsapp_opted_in_at' => now(),
    'whatsapp_opted_out_at' => null,
]);
echo "✅ WhatsApp number updated\n\n";

// Send critical update
$challenge = \App\Models\Challenge::first();

if (!$challenge) {
    echo "❌ No challenges found\n";
    exit(1);
}

echo "🚨 Creating critical update notification...\n";
echo "   🏆 Challenge: {$challenge->title}\n";

$notification = WhatsAppNotificationService::queueCriticalUpdate($user, $challenge->id);

if ($notification) {
    echo "   ✅ Notification created!\n";
    echo "   📝 ID: #{$notification->id}\n";
    echo "   🎯 Type: {$notification->type}\n";
    echo "   ⏱️  Status: {$notification->status}\n\n";

    echo "📨 Message Preview:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Mindova Notification\n\n";
    echo "Critical update on challenge\n";
    echo "\"{$challenge->title}\":\n";
    echo "Please check the latest update.\n\n";
    echo "View details:\n";
    echo url('/challenges/' . $challenge->id) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    echo "✅ Queued! Running queue worker...\n";
} else {
    echo "❌ Failed to create notification (might be duplicate)\n";
    exit(1);
}
