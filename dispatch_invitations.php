<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Challenge;
use App\Jobs\DecomposeChallengeTasks;
use App\Jobs\MatchVolunteersToTasks;

echo "🚀 Dispatching task decomposition and volunteer matching jobs...\n\n";

// Challenges that need task decomposition (no tasks created yet)
$challengesToDecompose = [8, 10, 12, 14];

foreach ($challengesToDecompose as $id) {
    $challenge = Challenge::find($id);
    if ($challenge) {
        echo "📋 Challenge {$id}: {$challenge->title}\n";
        echo "   Score: {$challenge->score}/10\n";

        // Dispatch task decomposition (will auto-trigger volunteer matching)
        DecomposeChallengeTasks::dispatch($challenge);
        echo "   ✅ Task decomposition job dispatched\n\n";
    }
}

// Challenge that has tasks but no assignments
echo "📢 Dispatching volunteer matching for existing tasks...\n";
$challenge1 = Challenge::find(1);
if ($challenge1) {
    echo "📋 Challenge 1: {$challenge1->title}\n";
    MatchVolunteersToTasks::dispatch($challenge1);
    echo "   ✅ Volunteer matching dispatched\n\n";
}

// Optionally re-match for challenges 2 and 3 to send more invitations
$challengesToRematch = [2, 3];
echo "🔄 Re-dispatching volunteer matching for more invitations...\n";
foreach ($challengesToRematch as $id) {
    $challenge = Challenge::find($id);
    if ($challenge) {
        echo "📋 Challenge {$id}: {$challenge->title}\n";
        MatchVolunteersToTasks::dispatch($challenge);
        echo "   ✅ Re-matching dispatched\n\n";
    }
}

echo "✅ All jobs dispatched successfully!\n";
echo "📌 Next step: Run queue worker to process jobs\n";
echo "   Command: php artisan queue:work\n";
