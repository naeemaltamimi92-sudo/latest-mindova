<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// This shared-hosting plan has no way to run a persistent `queue:work`
// daemon (no Supervisor/systemd access - see supervisor/mindova-worker.conf,
// which assumes a VPS and was never actually usable on this host). Instead,
// the cPanel cron below runs `schedule:run` every minute, and this drains
// whatever's currently queued and exits - the standard pattern for running
// Laravel queues on shared hosting. Every AI analysis job (CV analysis,
// challenge brief/complexity/decomposition, volunteer matching, team
// formation, idea/comment/solution scoring) goes through this queue, so
// without it nothing gets processed at all.
Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=5')
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();

// Self-heals challenges/volunteers stuck in pending/processing/failed AI
// analysis states. The command has its own per-record 10-minute dedup cache,
// so running this frequently is safe and won't cause duplicate dispatches.
Schedule::command('analysis:retry')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->onOneServer();

// Prunes openai_requests rows past the configured retention window
// (see config('ai.request_log_retention_days')) - that table stores full
// AI prompt/response text, which can include PII from uploaded CVs.
Schedule::command('ai-requests:prune')
    ->daily()
    ->onOneServer();
