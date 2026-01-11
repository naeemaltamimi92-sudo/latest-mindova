<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\WhatsAppNotificationService;

echo "📱 Sending WhatsApp to +962775575110\n";
echo "====================================\n\n";

$phoneNumber = '+962775575110';

// Get first user
$user = User::first();

if (!$user) {
    echo "❌ No users found\n";
    exit(1);
}

echo "👤 User: {$user->name}\n";
echo "📞 Updating phone to: {$phoneNumber}\n\n";

// Update WhatsApp number
$user->update([
    'whatsapp_opt_in' => true,
    'whatsapp_number' => $phoneNumber,
    'whatsapp_opted_in_at' => now(),
    'whatsapp_opted_out_at' => null,
]);
echo "✅ WhatsApp number updated\n\n";

// Try team invitation this time (different notification type)
$team = \App\Models\Team::first();

if (!$team) {
    echo "❌ No teams found\n";
    exit(1);
}

echo "📧 Creating team invitation notification...\n";
$challenge = $team->challenge;
echo "   🏆 Challenge: {$challenge->title}\n";
echo "   👥 Team: {$team->name}\n";

$notification = WhatsAppNotificationService::queueTeamInvitation($user, $team->id);

if ($notification) {
    echo "   ✅ Notification created!\n";
    echo "   📝 ID: #{$notification->id}\n";
    echo "   🎯 Type: {$notification->type}\n";
    echo "   ⏱️  Status: {$notification->status}\n\n";

    echo "📨 Message Preview:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Mindova Notification\n\n";
    echo "You have been invited to join a micro-team\n";
    echo "for the challenge \"{$challenge->title}\".\n\n";
    echo "View details:\n";
    echo url('/teams/' . $team->id) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    echo "✅ Queued! Running queue worker...\n";
} else {
    echo "❌ Failed to create notification (might be duplicate)\n";
    exit(1);
}
