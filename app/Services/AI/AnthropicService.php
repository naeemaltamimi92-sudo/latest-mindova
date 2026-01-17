<?php

namespace App\Services\AI;

use App\Models\OpenAIRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class AnthropicService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('ai.anthropic.base_url', 'https://api.anthropic.com');
        $this->apiKey = config('ai.anthropic.api_key');
    }

    /**
     * The model to use for this service.
     */
    abstract protected function getModel(): string;

    /**
     * The request type for logging purposes.
     */
    abstract protected function getRequestType(): string;

    /**
     * Make a text request to Anthropic Claude.
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
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->timeout(config('ai.anthropic.timeout', 120))
            ->post("{$this->baseUrl}/v1/messages", [
                'model' => $model,
                'max_tokens' => (int) config('ai.anthropic.max_tokens', 4096),
                'system' => $options['system_prompt'] ?? 'You are a helpful assistant that provides structured JSON responses.',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if (!$response->successful()) {
                throw new \Exception('Anthropic API error: ' . $response->body());
            }

            $duration = (microtime(true) - $startTime) * 1000;
            $responseData = $response->json();

            $content = $responseData['content'][0]['text'] ?? '';

            // Strip markdown code blocks if present
            $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
            $content = preg_replace('/\s*```$/i', '', $content);
            $content = trim($content);

            // Sanitize control characters before JSON parsing
            $content = $this->sanitizeJsonString($content);

            $parsedResponse = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse JSON response: ' . json_last_error_msg());
            }

            $this->logRequest(
                prompt: $prompt,
                response: $content,
                tokensPrompt: $responseData['usage']['input_tokens'] ?? 0,
                tokensCompletion: $responseData['usage']['output_tokens'] ?? 0,
                tokensTotal: ($responseData['usage']['input_tokens'] ?? 0) + ($responseData['usage']['output_tokens'] ?? 0),
                durationMs: $duration,
                status: 'success',
                relatedType: $relatedType,
                relatedId: $relatedId
            );

            return $parsedResponse;

        } catch (\Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;

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

            Log::error('Anthropic request failed', [
                'service' => static::class,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Make a request with a PDF document to Anthropic Claude.
     */
    protected function makeDocumentRequest(
        string $prompt,
        string $filePath,
        array $options = [],
        ?string $relatedType = null,
        ?int $relatedId = null
    ): array {
        $startTime = microtime(true);
        $model = $this->getModel();

        try {
            $fullPath = Storage::path($filePath);

            if (!file_exists($fullPath)) {
                throw new \Exception("File not found: {$filePath}");
            }

            $fileContent = file_get_contents($fullPath);
            $base64Data = base64_encode($fileContent);
            $mediaType = $this->getMediaType($filePath);

            $content = [
                [
                    'type' => 'document',
                    'source' => [
                        'type' => 'base64',
                        'media_type' => $mediaType,
                        'data' => $base64Data,
                    ],
                ],
                [
                    'type' => 'text',
                    'text' => $prompt,
                ],
            ];

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->timeout(config('ai.anthropic.timeout', 120))
            ->post("{$this->baseUrl}/v1/messages", [
                'model' => $model,
                'max_tokens' => (int) config('ai.anthropic.max_tokens', 4096),
                'system' => $options['system_prompt'] ?? 'You are a helpful assistant that provides structured JSON responses.',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $content,
                    ],
                ],
            ]);

            if (!$response->successful()) {
                throw new \Exception('Anthropic API error: ' . $response->body());
            }

            $duration = (microtime(true) - $startTime) * 1000;
            $responseData = $response->json();

            $responseContent = $responseData['content'][0]['text'] ?? '';

            // Strip markdown code blocks if present
            $responseContent = preg_replace('/^```(?:json)?\s*/i', '', $responseContent);
            $responseContent = preg_replace('/\s*```$/i', '', $responseContent);
            $responseContent = trim($responseContent);

            // Sanitize control characters before JSON parsing
            $responseContent = $this->sanitizeJsonString($responseContent);

            $parsedResponse = json_decode($responseContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse JSON response: ' . json_last_error_msg());
            }

            $this->logRequest(
                prompt: "[Document: {$filePath}] {$prompt}",
                response: $responseContent,
                tokensPrompt: $responseData['usage']['input_tokens'] ?? 0,
                tokensCompletion: $responseData['usage']['output_tokens'] ?? 0,
                tokensTotal: ($responseData['usage']['input_tokens'] ?? 0) + ($responseData['usage']['output_tokens'] ?? 0),
                durationMs: $duration,
                status: 'success',
                relatedType: $relatedType,
                relatedId: $relatedId
            );

            return $parsedResponse;

        } catch (\Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;

            $this->logRequest(
                prompt: "[Document: {$filePath}] {$prompt}",
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

            Log::error('Anthropic document request failed', [
                'service' => static::class,
                'file_path' => $filePath,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get the media type for a file.
     */
    protected function getMediaType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    /**
     * Log the request to the database.
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
     * Calculate the cost of a request.
     */
    protected function calculateCost(string $model, int $tokensPrompt, int $tokensCompletion): float
    {
        $pricing = config('ai.pricing.' . $model, [
            'input' => 0.0,
            'output' => 0.0,
        ]);

        $promptCost = ($tokensPrompt / 1_000_000) * ($pricing['input'] ?? 0.0);
        $completionCost = ($tokensCompletion / 1_000_000) * ($pricing['output'] ?? 0.0);

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

    /**
     * Sanitize JSON string by escaping control characters within string values.
     *
     * Claude sometimes returns JSON with unescaped control characters (newlines, tabs)
     * inside string values, which causes json_decode() to fail.
     */
    protected function sanitizeJsonString(string $json): string
    {
        return preg_replace_callback(
            '/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/',
            function ($matches) {
                $str = $matches[1];
                $str = preg_replace_callback(
                    '/[\x00-\x1F]/',
                    function ($m) {
                        $char = ord($m[0]);
                        return match ($char) {
                            0x09 => '\\t',
                            0x0A => '\\n',
                            0x0D => '\\r',
                            default => sprintf('\\u%04X', $char),
                        };
                    },
                    $str
                );
                return '"' . $str . '"';
            },
            $json
        );
    }
}
