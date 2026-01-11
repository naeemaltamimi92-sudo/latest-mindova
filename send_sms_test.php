<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Services\NotificationChannelService;

echo "📧 SMS Notification Test\n";
echo "=======================\n\n";

// Get first user
$user = User::first();

if (!$user) {
    echo "❌ No users found\n";
    exit(1);
}

echo "👤 User: {$user->name}\n";
echo "📞 Phone: {$user->whatsapp_number}\n\n";

// Check SMS configuration
$smsFrom = config('services.twilio.sms_from');
$smsEnabled = config('services.twilio.sms_enabled');

echo "📋 SMS Configuration:\n";
echo "   From Number: " . ($smsFrom ?: '❌ NOT SET') . "\n";
echo "   Enabled: " . ($smsEnabled ? '✅ YES' : '❌ NO') . "\n\n";

if (empty($smsFrom)) {
    echo "⚠️  SMS number not configured!\n\n";
    echo "To enable SMS:\n";
    echo "1. Buy a Twilio SMS number:\n";
    echo "   https://console.twilio.com/us1/develop/phone-numbers/manage/search\n\n";
    echo "2. Add to .env:\n";
    echo "   TWILIO_SMS_FROM=+1234567890\n";
    echo "   TWILIO_SMS_ENABLED=true\n\n";
    exit(1);
}

echo "🚀 Sending test SMS...\n\n";

try {
    $result = NotificationChannelService::send(
        user: $user,
        templateName: 'task_assigned',
        variables: [
            'task_title' => 'Test SMS Notification',
            'link' => url('/tasks/1'),
        ],
        preferredChannel: 'sms'  // Force SMS
    );

    if ($result['success']) {
        echo "✅ SMS SENT SUCCESSFULLY!\n\n";
        echo "   Channel: {$result['channel']}\n";
        echo "   Message ID: {$result['message_id']}\n\n";
        echo "📨 Message sent to {$user->whatsapp_number}\n";
        echo "   Check your phone! 📱\n";
    } else {
        echo "❌ SMS FAILED\n\n";
        echo "   Error: {$result['error']}\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n✅ Test complete!\n";
