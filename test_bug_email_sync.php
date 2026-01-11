<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BugReport;
use App\Models\User;
use App\Mail\BugReportSubmitted;
use Illuminate\Support\Facades\Mail;

echo "🧪 Bug Report Email Test (Synchronous)\n";
echo "======================================\n\n";

// Get the most recent bug report
$bugReport = BugReport::with('user')->orderBy('created_at', 'desc')->first();

if (!$bugReport) {
    echo "❌ No bug reports found. Creating a test one...\n\n";

    $user = User::first();
    if (!$user) {
        echo "❌ No users found. Please create a user first.\n";
        exit(1);
    }

    $bugReport = BugReport::create([
        'user_id' => $user->id,
        'issue_type' => 'bug',
        'description' => 'Test: The upload button is not responding when clicked. This is blocking my work.',
        'current_page' => '/dashboard/profile',
        'screenshot' => null,
        'blocked_user' => true,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Test Browser',
        'status' => 'new',
    ]);

    echo "✅ Test bug report created (ID: {$bugReport->id})\n\n";
}

echo "📋 Bug Report Details:\n";
echo "----------------------\n";
echo "ID: {$bugReport->id}\n";
echo "Type: {$bugReport->getIssueTypeLabel()}\n";
echo "Description: " . substr($bugReport->description, 0, 60) . "...\n";
echo "Page: {$bugReport->current_page}\n";
echo "Critical: " . ($bugReport->blocked_user ? 'YES ⚠️' : 'No') . "\n";
echo "Reporter: " . ($bugReport->user ? $bugReport->user->name : 'Anonymous') . "\n";
echo "Status: {$bugReport->status}\n\n";

echo "📧 Email Configuration:\n";
echo "-----------------------\n";
echo "Recipient: " . config('mail.owner_email') . "\n";
echo "Mail Driver: " . config('mail.default') . "\n";
echo "From: " . config('mail.from.address') . "\n\n";

// Force synchronous sending (bypass queue)
echo "📤 Sending email (synchronous)...\n";
try {
    // Create the mailable
    $mailable = new BugReportSubmitted($bugReport);

    // Get the subject
    $subject = $mailable->envelope()->subject;
    echo "   Subject: {$subject}\n";

    // Send immediately (not queued)
    Mail::to(config('mail.owner_email'))->send($mailable);

    echo "   ✅ Email sent successfully!\n\n";

    // Since we're using 'log' driver, check the log
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        echo "📄 Checking laravel.log...\n";

        $logContent = file_get_contents($logPath);
        $lines = explode("\n", $logContent);

        // Find recent email-related lines
        $emailLines = [];
        $foundEmail = false;
        foreach (array_reverse($lines) as $line) {
            if (strpos($line, 'Message-ID:') !== false) {
                $foundEmail = true;
            }
            if ($foundEmail) {
                $emailLines[] = $line;
                if (count($emailLines) >= 50) break;
            }
        }

        if ($foundEmail) {
            echo "   ✅ Email found in log\n\n";
            echo "📬 Email Preview (last 30 lines):\n";
            echo "=================================\n";
            foreach (array_slice(array_reverse($emailLines), 0, 30) as $line) {
                echo $line . "\n";
            }
        } else {
            echo "   ⚠️  Email not found in recent log entries\n";
        }
    }

} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n✅ Test complete!\n";
