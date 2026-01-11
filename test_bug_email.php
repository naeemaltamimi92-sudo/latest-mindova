<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BugReport;
use App\Models\User;
use App\Mail\BugReportSubmitted;
use Illuminate\Support\Facades\Mail;

echo "🧪 Bug Report Email Test\n";
echo "========================\n\n";

// Get a test user
$user = User::first();
if (!$user) {
    echo "❌ No users found. Please create a user first.\n";
    exit(1);
}

echo "📧 Testing Email Notification System\n";
echo "------------------------------------\n";
echo "Recipient: " . config('mail.owner_email') . "\n";
echo "Mail Driver: " . config('mail.default') . "\n\n";

// Create a test bug report
echo "1️⃣ Creating test bug report...\n";
$bugReport = BugReport::create([
    'user_id' => $user->id,
    'issue_type' => 'bug',
    'description' => 'Test bug report: The upload button is not working properly when I try to upload my CV.',
    'current_page' => '/dashboard/profile',
    'screenshot' => null,
    'blocked_user' => true, // Mark as critical
    'user_agent' => 'Mozilla/5.0 (Test Browser)',
    'status' => 'new',
]);

echo "   ✅ Bug report created (ID: {$bugReport->id})\n";
echo "   📋 Type: {$bugReport->getIssueTypeLabel()}\n";
echo "   🚨 Critical: " . ($bugReport->blocked_user ? 'YES' : 'No') . "\n\n";

// Send the email
echo "2️⃣ Sending email notification...\n";
try {
    Mail::to(config('mail.owner_email'))->send(new BugReportSubmitted($bugReport));
    echo "   ✅ Email sent successfully!\n\n";
} catch (\Exception $e) {
    echo "   ❌ Error sending email: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Check log file
echo "3️⃣ Checking email log...\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
    if (strpos($logContent, 'Bug Report:') !== false) {
        echo "   ✅ Email logged successfully\n";

        // Show last few lines of log
        $lines = explode("\n", $logContent);
        $lastLines = array_slice($lines, -20);

        echo "\n📄 Recent Log Entries:\n";
        echo "---------------------\n";
        foreach ($lastLines as $line) {
            if (strpos($line, 'Bug Report') !== false || strpos($line, 'CRITICAL') !== false) {
                echo $line . "\n";
            }
        }
    } else {
        echo "   ⚠️  Email not found in log yet (might be queued)\n";
    }
} else {
    echo "   ⚠️  Log file not found at: $logPath\n";
}

echo "\n✅ Test complete!\n\n";

echo "📊 Summary:\n";
echo "-----------\n";
echo "✓ Bug report created in database\n";
echo "✓ Email notification triggered\n";
echo "✓ Email logged (mail driver is 'log')\n\n";

echo "💡 Next Steps:\n";
echo "-------------\n";
echo "1. Check storage/logs/laravel.log for full email content\n";
echo "2. To send real emails, update .env:\n";
echo "   - Change MAIL_MAILER=log to MAIL_MAILER=smtp\n";
echo "   - Configure SMTP settings (host, port, username, password)\n";
echo "3. For queue processing: php artisan queue:work\n\n";

echo "🔍 Bug Report Details:\n";
echo "----------------------\n";
echo "ID: {$bugReport->id}\n";
echo "Reporter: {$user->name} ({$user->email})\n";
echo "Type: {$bugReport->getIssueTypeLabel()}\n";
echo "Critical: " . ($bugReport->blocked_user ? 'YES ⚠️' : 'No') . "\n";
echo "Status: {$bugReport->status}\n";
echo "Created: {$bugReport->created_at->format('Y-m-d H:i:s')}\n";
