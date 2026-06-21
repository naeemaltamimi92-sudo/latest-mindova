<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Self-heals challenges/volunteers stuck in pending/processing/failed AI
// analysis states. The command has its own per-record 10-minute dedup cache,
// so running this frequently is safe and won't cause duplicate dispatches.
Schedule::command('analysis:retry')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->onOneServer();
