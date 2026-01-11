<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Http;

echo "🌐 Meta WhatsApp Cloud API Test\n";
echo "===============================\n\n";

// Get configuration
$accessToken = config('services.meta.whatsapp_access_token');
$phoneNumberId = config('services.meta.whatsapp_phone_number_id');
$enabled = config('services.meta.whatsapp_cloud_enabled');

echo "📋 Configuration Status:\n";
echo "   Enabled: " . ($enabled ? '✅ YES' : '❌ NO') . "\n";
echo "   Access Token: " . ($accessToken ? '✅ SET (' . substr($accessToken, 0, 20) . '...)' : '❌ NOT SET') . "\n";
echo "   Phone Number ID: " . ($phoneNumberId ?: '❌ NOT SET') . "\n\n";

if (!$accessToken || !$phoneNumberId) {
    echo "⚠️  Meta WhatsApp not configured!\n\n";
    echo "To configure:\n";
    echo "1. Go to: https://developers.facebook.com/\n";
    echo "2. Create an app and add WhatsApp product\n";
    echo "3. Copy credentials and add to .env:\n";
    echo "   META_WHATSAPP_CLOUD_ENABLED=true\n";
    echo "   META_WHATSAPP_ACCESS_TOKEN=EAAxxxxxxxxx\n";
    echo "   META_WHATSAPP_PHONE_NUMBER_ID=123456789\n\n";
    exit(1);
}

// Get user
$user = User::first();
if (!$user || !$user->whatsapp_number) {
    echo "❌ No user with phone number found\n";
    exit(1);
}

$toNumber = ltrim($user->whatsapp_number, '+');

echo "📞 Sending test message to: {$user->whatsapp_number}\n\n";

try {
    // Send simple text message via Meta Cloud API
    $response = Http::withToken($accessToken)
        ->post("https://graph.facebook.com/v21.0/{$phoneNumberId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $toNumber,
            'type' => 'text',
            'text' => [
                'body' => "Mindova Notification\n\nThis is a test message from Meta WhatsApp Cloud API.\n\nYou are receiving WhatsApp messages WITHOUT Twilio! ✅",
            ],
        ]);

    if ($response->successful()) {
        $messageId = $response->json('messages.0.id');

        echo "✅ MESSAGE SENT SUCCESSFULLY!\n\n";
        echo "   Message ID: {$messageId}\n";
        echo "   Sent to: {$user->whatsapp_number}\n";
        echo "   Channel: Meta WhatsApp Cloud API (Direct)\n\n";
        echo "📱 Check your WhatsApp!\n\n";
        echo "✅ You are now sending WhatsApp messages WITHOUT Twilio!\n";
    } else {
        $error = $response->json('error.message', 'Unknown error');
        $errorCode = $response->json('error.code', 'N/A');

        echo "❌ FAILED TO SEND\n\n";
        echo "   Error Code: {$errorCode}\n";
        echo "   Error: {$error}\n\n";

        if (str_contains($error, 'recipient phone number not in allowed list')) {
            echo "⚠️  NOTE: Test numbers can only send to specific recipients.\n";
            echo "   You need to add +{$toNumber} to your allowed list in Meta:\n";
            echo "   https://developers.facebook.com/apps/YOUR_APP_ID/whatsapp-business/wa-dev-console/\n\n";
        }
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n✅ Test complete!\n";
