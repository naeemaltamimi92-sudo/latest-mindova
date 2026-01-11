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
            'expected_token' => $verifyToken,
            'received_token' => $token,
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
        // Verify signature (optional but recommended)
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();

        if ($signature && !$this->whatsAppService->verifyWebhookSignature($payload, $signature)) {
            Log::channel('whatsapp')->warning('Invalid webhook signature');
            return response('Invalid signature', 401);
        }

        // Log incoming webhook
        Log::channel('whatsapp')->info('Webhook received', [
            'payload' => $request->all(),
        ]);

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
