<?php

namespace App\Services\AI;

use App\Models\OpenAIRequest;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

abstract class OpenAIService
{
    /**
     * The model to use for this service.
     */
    abstract protected function getModel(): string;

    /**
     * The request type for logging purposes.
     */
    abstract protected function getRequestType(): string;

    /**
     * Make a request to OpenAI and log it.
     *
     * @param string $prompt The prompt to send
     * @param array $options Additional options for the request
     * @param string|null $relatedType The model type this request relates to
     * @param int|null $relatedId The model ID this request relates to
     * @return array The parsed response with confidence score
     */
    protected function makeRequest(
        string $prompt,
        array $options = [],
        ?string $relatedType = null,
        ?int $relatedId = null
    ): array {
        $startTime = microtime(true);
        $model = $this->getModel();

        try {
            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $options['system_prompt'] ?? 'You are a helpful assistant that provides structured JSON responses.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => $options['temperature'] ?? 0.7,
                'response_format' => ['type' => 'json_object'],
            ]);

            $duration = (microtime(true) - $startTime) * 1000;

            $content = $response->choices[0]->message->content;
            $parsedResponse = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse JSON response: ' . json_last_error_msg());
            }

            // Log the request
            $this->logRequest(
                prompt: $prompt,
                response: $content,
                tokensPrompt: $response->usage->promptTokens,
                tokensCompletion: $response->usage->completionTokens,
                tokensTotal: $response->usage->totalTokens,
                durationMs: $duration,
                status: 'success',
                relatedType: $relatedType,
                relatedId: $relatedId
            );

            return $parsedResponse;

        } catch (\Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;

            // Log the failed request
            $this->logRequest(
                prompt: $prompt,
                response: null,
                tokensPrompt: 0,
                tokensCompletion: 0,
                tokensTotal: 0,
                durationMs: $duration,
                status: 'failed',
                relatedType: $relatedType,
                relatedId: $relatedId,
                errorMessage: $e->getMessage()
            );

            Log::error('OpenAI request failed', [
                'service' => static::class,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Log the OpenAI request to the database.
     */
    protected function logRequest(
        string $prompt,
        ?string $response,
        int $tokensPrompt,
        int $tokensCompletion,
        int $tokensTotal,
        float $durationMs,
        string $status,
        ?string $relatedType = null,
        ?int $relatedId = null,
        ?string $errorMessage = null
    ): void {
        $model = $this->getModel();
        $costUsd = $this->calculateCost($model, $tokensPrompt, $tokensCompletion);

        OpenAIRequest::create([
            'request_type' => $this->getRequestType(),
            'model' => $model,
            'prompt' => $prompt,
            'response' => $response,
            'tokens_prompt' => $tokensPrompt,
            'tokens_completion' => $tokensCompletion,
            'tokens_total' => $tokensTotal,
            'cost_usd' => $costUsd,
            'duration_ms' => $durationMs,
            'status' => $status,
            'error_message' => $errorMessage,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'created_at' => now(),
        ]);
    }

    /**
     * Calculate the cost of an OpenAI request.
     */
    protected function calculateCost(string $model, int $tokensPrompt, int $tokensCompletion): float
    {
        $pricing = config('ai.pricing.' . $model, [
            'input' => 0.0,
            'output' => 0.0,
        ]);

        $promptCost = ($tokensPrompt / 1_000_000) * ($pricing['input'] ?? $pricing['prompt'] ?? 0.0);
        $completionCost = ($tokensCompletion / 1_000_000) * ($pricing['output'] ?? $pricing['completion'] ?? 0.0);

        return $promptCost + $completionCost;
    }

    /**
     * Validate that the response contains required fields.
     */
    protected function validateResponse(array $response, array $requiredFields): bool
    {
        foreach ($requiredFields as $field) {
            if (!isset($response[$field])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if confidence score meets the threshold.
     */
    protected function meetsConfidenceThreshold(float $confidence): bool
    {
        $threshold = config('ai.confidence_threshold.' . $this->getRequestType(), 70.0);
        return $confidence >= $threshold;
    }
}
