<?php

namespace App\Console\Commands;

use App\Models\OpenAIRequest;
use Illuminate\Console\Command;

class PruneAiRequestLogs extends Command
{
    /**
     * openai_requests stores the full prompt/response text of every AI
     * call, which can include full CV contents and other PII extracted
     * from uploaded documents, with no retention limit. This prunes rows
     * past a configurable age so that data doesn't accumulate forever.
     */
    protected $signature = 'ai-requests:prune {--days= : Override the retention window in days}';

    protected $description = 'Delete AI request logs (prompt/response text) older than the retention window';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?? config('ai.request_log_retention_days', 90));

        $deleted = OpenAIRequest::where('created_at', '<', now()->subDays($days))->delete();

        $this->info("Pruned {$deleted} AI request log(s) older than {$days} days.");

        return self::SUCCESS;
    }
}
