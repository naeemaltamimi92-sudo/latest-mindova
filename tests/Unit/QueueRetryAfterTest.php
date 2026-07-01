<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Regression test for a confirmed production incident: the database
 * queue's retry_after (90s, the framework default) was shorter than
 * every AI-pipeline job's own $timeout (180-900s). A job interrupted
 * mid-run (e.g. the worker process killed by shared-hosting resource
 * limits) became eligible for a second, concurrent worker to pick up
 * before Laravel could record its actual outcome - observed in
 * production as a job attempted 10 times despite tries=5.
 */
class QueueRetryAfterTest extends TestCase
{
    public function test_database_queue_retry_after_exceeds_every_jobs_timeout(): void
    {
        $retryAfter = config('queue.connections.database.retry_after');

        $jobFiles = glob(app_path('Jobs/*.php'));
        $longestTimeout = 0;

        foreach ($jobFiles as $file) {
            $contents = file_get_contents($file);

            if (preg_match('/public\s+int\s+\$timeout\s*=\s*(\d+)/', $contents, $matches)) {
                $longestTimeout = max($longestTimeout, (int) $matches[1]);
            }
        }

        $this->assertGreaterThan(0, $longestTimeout, 'Expected to find at least one job with a $timeout property.');

        $this->assertGreaterThan(
            $longestTimeout,
            $retryAfter,
            "queue.connections.database.retry_after ({$retryAfter}s) must exceed the longest job timeout ({$longestTimeout}s), or an interrupted job can be picked up by a second worker before it properly completes or fails."
        );
    }
}
