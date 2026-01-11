<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BugReport;
use App\Models\User;

echo "🧪 Bug Report System Test\n";
echo "========================\n\n";

// Check if bug_reports table exists
try {
    $count = BugReport::count();
    echo "✅ Bug reports table exists\n";
    echo "📊 Total bug reports: $count\n\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Show recent bug reports
if ($count > 0) {
    echo "📋 Recent Bug Reports:\n";
    echo "---------------------\n";

    $reports = BugReport::with('user')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    foreach ($reports as $report) {
        echo "\n";
        echo "ID: {$report->id}\n";
        echo "Type: {$report->getIssueTypeLabel()}\n";
        echo "Description: " . substr($report->description, 0, 50) . "...\n";
        echo "Page: {$report->current_page}\n";
        echo "Blocked User: " . ($report->blocked_user ? 'YES ⚠️' : 'No') . "\n";
        echo "Status: {$report->status}\n";
        echo "Reported by: " . ($report->user ? $report->user->name : 'Anonymous') . "\n";
        echo "Created: {$report->created_at->diffForHumans()}\n";
        echo "---\n";
    }
} else {
    echo "📭 No bug reports yet.\n\n";
    echo "To test:\n";
    echo "1. Login to http://localhost/mindova/dashboard\n";
    echo "2. Look for 'Report an Issue' button (bottom-right)\n";
    echo "3. Submit a test bug report\n";
    echo "4. Run this script again to see the result\n";
}

echo "\n✅ Test complete!\n";
