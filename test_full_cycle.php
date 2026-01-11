<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Challenge;
use App\Jobs\AnalyzeChallengeBrief;
use Illuminate\Support\Facades\Log;

// Get challenge #2
$challenge = Challenge::find(2);

if (!$challenge) {
    echo "Challenge #2 not found!\n";
    exit(1);
}

echo "Starting full cycle test for Challenge #2\n";
echo "Challenge: {$challenge->title}\n";
echo "Field: {$challenge->field}\n";
echo "Current status: {$challenge->status}\n\n";

// Dispatch the first job in the pipeline
echo "Dispatching AnalyzeChallengeBrief job...\n";
AnalyzeChallengeBrief::dispatch($challenge);

echo "\nJob dispatched! Monitor logs with:\n";
echo "php artisan queue:work --tries=3 --timeout=600\n\n";
echo "Or check logs in real-time:\n";
echo "tail -f storage/logs/laravel.log | grep -E '(challenge_id|volunteer|task|match)'\n";
