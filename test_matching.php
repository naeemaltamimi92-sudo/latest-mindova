<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Challenge;
use App\Jobs\MatchVolunteersToTasks;

// Get challenge #2
$challenge = Challenge::find(2);

if (!$challenge) {
    echo "Challenge #2 not found!\n";
    exit(1);
}

echo "Dispatching MatchVolunteersToTasks job for Challenge #2\n";
echo "Challenge field: {$challenge->field}\n\n";

// Dispatch matching job
MatchVolunteersToTasks::dispatch($challenge);

echo "Job dispatched! Check logs for results.\n";
