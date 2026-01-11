<?php

namespace App\Jobs\Concerns;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Trait for robust job handling with enhanced error recovery,
 * rate limiting, circuit breaker, and exponential backoff.
 */
trait RobustJob
{
    // Note: $tries, $timeout, and other job properties should be defined in the job class itself
    // to allow customization per job type. This trait provides methods for robust handling.

    /**
     * Calculate the number of seconds to wait before retrying the job.
     * Uses exponential backoff with jitter.
     */
    public function backoff(): array
    {
        return [
            10,     // 10 seconds for first retry
            30,     // 30 seconds for second retry
            60,     // 1 minute for third retry
            120,    // 2 minutes for fourth retry
            300,    // 5 minutes for fifth retry
        ];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(2);
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->getJobIdentifier();
    }

    /**
     * Get the job identifier for logging and caching.
     */
    protected function getJobIdentifier(): string
    {
        $className = class_basename(static::class);
        $modelId = $this->getModelId();
        return "{$className}:{$modelId}";
    }

    /**
     * Get the model ID for this job.
     */
    protected function getModelId(): int|string
    {
        // Override in child classes if needed
        foreach (get_object_vars($this) as $property => $value) {
            if (is_object($value) && method_exists($value, 'getKey')) {
                return $value->getKey();
            }
        }
        return 0;
    }

    /**
     * Check if the circuit breaker is open (too many failures).
     */
    protected function isCircuitBreakerOpen(): bool
    {
        $key = 'circuit_breaker:' . class_basename(static::class);
        $failures = Cache::get($key, 0);

        // Open circuit if more than 10 failures in the last 5 minutes
        return $failures >= 10;
    }

    /**
     * Record a failure for circuit breaker.
     */
    protected function recordCircuitBreakerFailure(): void
    {
        $key = 'circuit_breaker:' . class_basename(static::class);
        $failures = Cache::get($key, 0) + 1;
        Cache::put($key, $failures, now()->addMinutes(5));
    }

    /**
     * Record a success for circuit breaker (reset).
     */
    protected function recordCircuitBreakerSuccess(): void
    {
        $key = 'circuit_breaker:' . class_basename(static::class);
        Cache::forget($key);
    }

    /**
     * Check if API rate limit is exceeded.
     */
    protected function isRateLimited(): bool
    {
        $key = 'rate_limit:openai_api';
        $requests = Cache::get($key, 0);

        // Limit to 50 requests per minute
        return $requests >= 50;
    }

    /**
     * Record an API request for rate limiting.
     */
    protected function recordApiRequest(): void
    {
        $key = 'rate_limit:openai_api';
        $requests = Cache::get($key, 0) + 1;
        Cache::put($key, $requests, now()->addMinute());
    }

    /**
     * Log job start with context.
     */
    protected function logJobStart(array $context = []): void
    {
        Log::info("[JOB START] {$this->getJobIdentifier()}", array_merge([
            'job' => class_basename(static::class),
            'attempt' => $this->attempts(),
            'max_tries' => $this->tries,
        ], $context));
    }

    /**
     * Log job completion with context.
     */
    protected function logJobComplete(array $context = []): void
    {
        Log::info("[JOB COMPLETE] {$this->getJobIdentifier()}", array_merge([
            'job' => class_basename(static::class),
            'attempts_used' => $this->attempts(),
        ], $context));
    }

    /**
     * Log job error with context.
     */
    protected function logJobError(Throwable $e, array $context = []): void
    {
        Log::error("[JOB ERROR] {$this->getJobIdentifier()}", array_merge([
            'job' => class_basename(static::class),
            'attempt' => $this->attempts(),
            'max_tries' => $this->tries,
            'error' => $e->getMessage(),
            'exception_class' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], $context));
    }

    /**
     * Log job failure (final) with context.
     */
    protected function logJobFailed(Throwable $e, array $context = []): void
    {
        Log::critical("[JOB FAILED PERMANENTLY] {$this->getJobIdentifier()}", array_merge([
            'job' => class_basename(static::class),
            'attempts_used' => $this->attempts(),
            'error' => $e->getMessage(),
            'exception_class' => get_class($e),
            'trace' => $e->getTraceAsString(),
        ], $context));
    }

    /**
     * Determine if the exception should cause the job to be released.
     */
    protected function shouldReleaseOnException(Throwable $e): bool
    {
        // Release on transient errors
        $transientErrors = [
            'Connection timed out',
            'cURL error',
            'Too Many Requests',
            'Service Unavailable',
            'Gateway Timeout',
            'rate limit',
            'timeout',
            'temporarily unavailable',
        ];

        foreach ($transientErrors as $error) {
            if (stripos($e->getMessage(), $error) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle job middleware for rate limiting and circuit breaker.
     */
    public function middleware(): array
    {
        return [
            new \Illuminate\Queue\Middleware\WithoutOverlapping($this->getJobIdentifier()),
        ];
    }

    /**
     * Execute with robust error handling wrapper.
     */
    protected function executeWithRobustHandling(callable $callback, array $context = []): mixed
    {
        // Check circuit breaker
        if ($this->isCircuitBreakerOpen()) {
            Log::warning("[CIRCUIT BREAKER OPEN] {$this->getJobIdentifier()} - Releasing job to retry later");
            $this->release(120); // Release for 2 minutes
            return null;
        }

        // Check rate limiting
        if ($this->isRateLimited()) {
            Log::warning("[RATE LIMITED] {$this->getJobIdentifier()} - Releasing job to retry later");
            $this->release(60); // Release for 1 minute
            return null;
        }

        $this->logJobStart($context);
        $this->recordApiRequest();

        try {
            $result = $callback();

            $this->recordCircuitBreakerSuccess();
            $this->logJobComplete($context);

            return $result;

        } catch (Throwable $e) {
            $this->recordCircuitBreakerFailure();
            $this->logJobError($e, $context);

            // Check if we should release instead of throwing
            if ($this->shouldReleaseOnException($e) && $this->attempts() < $this->tries) {
                $backoff = $this->backoff()[$this->attempts() - 1] ?? 300;
                Log::info("[JOB RELEASING] {$this->getJobIdentifier()} - Will retry in {$backoff} seconds");
                $this->release($backoff);
                return null;
            }

            throw $e;
        }
    }

    /**
     * Store the job failure reason for debugging.
     */
    protected function storeFailureReason(string $reason, array $details = []): void
    {
        $key = 'job_failure:' . $this->getJobIdentifier();
        Cache::put($key, [
            'reason' => $reason,
            'details' => $details,
            'failed_at' => now()->toDateTimeString(),
            'attempts' => $this->attempts(),
        ], now()->addDay());
    }

    /**
     * Get the failure reason if previously stored.
     */
    protected function getStoredFailureReason(): ?array
    {
        $key = 'job_failure:' . $this->getJobIdentifier();
        return Cache::get($key);
    }
}
