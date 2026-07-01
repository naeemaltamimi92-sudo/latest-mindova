<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppCloudService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Webhook Controller
 *
 * Handles incoming webhooks from Meta WhatsApp Business API
 * - Webhook verification (GET)
 * - Message and status updates (POST)
 */
class WhatsAppWebhookController extends Controller
{
    protected WhatsAppCloudService $whatsAppService;

    public function __construct(WhatsAppCloudService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Verify webhook (GET request from Meta)
     *
     * Meta sends a GET request to verify the webhook URL.
     * We must respond with the challenge token if verification is successful.
     */
    public function verify(Request $request): Response
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = config('whatsapp.verify_token');

        Log::channel('whatsapp')->info('Webhook verification attempt', [
            'mode' => $mode,
            'token_match' => $token === $verifyToken,
        ]);

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::channel('whatsapp')->info('Webhook verified successfully');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::channel('whatsapp')->warning('Webhook verification failed', [
            'mode' => $mode,
            'token_match' => $token === $verifyToken,
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming webhook (POST request from Meta)
     *
     * Processes:
     * - Incoming messages
     * - Message status updates (sent, delivered, read)
     * - Errors
     */
    public function handle(Request $request): Response
    {
        // Verify signature (required - rejects unsigned/forged requests)
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();

        if (!$signature || !$this->whatsAppService->verifyWebhookSignature($payload, $signature)) {
            Log::channel('whatsapp')->warning('Missing or invalid webhook signature');
            return response('Invalid signature', 401);
        }

        // Log incoming webhook - summary only, not the raw payload, which
        // contains message text and phone numbers (PII).
        Log::channel('whatsapp')->info('Webhook received', $this->summarizeWebhook($request->all()));

        try {
            // Process the webhook data
            $this->whatsAppService->processWebhook($request->all());

            // Always respond with 200 to acknowledge receipt
            return response('OK', 200);
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Still return 200 to prevent Meta from retrying
            return response('OK', 200);
        }
    }

    /**
     * Reduce a WhatsApp Cloud API webhook payload to log-safe metadata -
     * message/status counts and types - without the message text or
     * phone numbers the full payload contains.
     */
    private function summarizeWebhook(array $data): array
    {
        $value = $data['entry'][0]['changes'][0]['value'] ?? [];
        $messages = $value['messages'] ?? [];
        $statuses = $value['statuses'] ?? [];

        return [
            'message_count' => count($messages),
            'message_types' => array_values(array_unique(array_column($messages, 'type'))),
            'status_count' => count($statuses),
            'status_types' => array_values(array_unique(array_column($statuses, 'status'))),
        ];
    }

    /**
     * Get webhook status (for debugging)
     */
    public function status(): array
    {
        return [
            'status' => 'active',
            'service' => $this->whatsAppService->getStatus(),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
