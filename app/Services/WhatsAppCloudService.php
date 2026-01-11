<?php

namespace App\Services;

use App\Models\User;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppNotification;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Meta WhatsApp Cloud API Service (Direct Integration) - Enhanced 2027
 *
 * Full Meta WhatsApp Business API integration with:
 * - Text, Template, Image, Document, Interactive messages
 * - Webhook handling for incoming messages
 * - Message status tracking
 * - Rate limiting and retry logic
 * - Media upload/download
 * - Business profile management
 *
 * Free tier: 1,000 conversations/month
 * Enable by setting META_WHATSAPP_CLOUD_ENABLED=true
 */
class WhatsAppCloudService
{
    private string $accessToken;
    private string $phoneNumberId;
    private string $businessAccountId;
    private string $apiVersion = 'v21.0';
    private string $baseUrl = 'https://graph.facebook.com';

    public function __construct()
    {
        $this->accessToken = config('whatsapp.access_token') ?? config('services.meta.whatsapp_access_token');
        $this->phoneNumberId = config('whatsapp.phone_number_id') ?? config('services.meta.whatsapp_phone_number_id');
        $this->businessAccountId = config('whatsapp.business_account_id') ?? config('services.meta.whatsapp_business_account_id');
        $this->apiVersion = config('whatsapp.api_version', 'v21.0');
    }

    /**
     * Get the full API URL for messages endpoint
     */
    protected function getMessagesUrl(): string
    {
        return "{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/messages";
    }

    /**
     * Get the full API URL for media upload endpoint
     */
    protected function getMediaUploadUrl(): string
    {
        return "{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/media";
    }

    /**
     * Send WhatsApp message using Meta Cloud API.
     *
     * @param User $user
     * @param string $templateName
     * @param array $variables
     * @return string Provider message ID
     * @throws Exception
     */
    public function sendMessage(User $user, string $templateName, array $variables): string
    {
        // Validate user has opted in
        if (!$user->hasWhatsAppEnabled()) {
            throw new Exception('User has not opted in for WhatsApp notifications');
        }

        // Validate configuration
        if (empty($this->accessToken) || empty($this->phoneNumberId)) {
            throw new Exception('Meta WhatsApp Cloud API not configured');
        }

        // Map to Meta template name (update after creating templates in Meta Business Manager)
        $metaTemplateName = $this->getMetaTemplateName($templateName);

        // Build template components
        $components = $this->buildTemplateComponents($templateName, $variables);

        // Send via Meta Cloud API
        $response = Http::withToken($this->accessToken)
            ->post("https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => ltrim($user->whatsapp_number, '+'),
                'type' => 'template',
                'template' => [
                    'name' => $metaTemplateName,
                    'language' => [
                        'code' => 'en',
                    ],
                    'components' => $components,
                ],
            ]);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown error');
            throw new Exception("Meta API error: {$error}");
        }

        $messageId = $response->json('messages.0.id');

        if (!$messageId) {
            throw new Exception('No message ID returned from Meta API');
        }

        return $messageId;
    }

    /**
     * Get Meta template name.
     *
     * TODO: Update these after creating templates in Meta Business Manager
     *
     * @param string $templateName
     * @return string
     * @throws Exception
     */
    private function getMetaTemplateName(string $templateName): string
    {
        $metaTemplateNames = [
            // TODO: Replace with actual template names from Meta Business Manager
            'team_invite' => env('META_TEMPLATE_TEAM_INVITE', 'mindova_team_invitation'),
            'task_assigned' => env('META_TEMPLATE_TASK_ASSIGNED', 'mindova_task_assignment'),
            'critical_update' => env('META_TEMPLATE_CRITICAL_UPDATE', 'mindova_critical_update'),
        ];

        if (!isset($metaTemplateNames[$templateName])) {
            throw new Exception("Meta template not found: {$templateName}");
        }

        return $metaTemplateNames[$templateName];
    }

    /**
     * Build template components for Meta API.
     */
    private function buildTemplateComponents(string $templateName, array $variables): array
    {
        // Meta WhatsApp templates use components with parameters
        switch ($templateName) {
            case 'team_invite':
                return [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $variables['challenge_title'] ?? ''],
                            ['type' => 'text', 'text' => $variables['link'] ?? ''],
                        ],
                    ],
                ];

            case 'task_assigned':
                return [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $variables['task_title'] ?? ''],
                            ['type' => 'text', 'text' => $variables['link'] ?? ''],
                        ],
                    ],
                ];

            case 'critical_update':
                return [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $variables['challenge_title'] ?? ''],
                            ['type' => 'text', 'text' => $variables['update_message'] ?? ''],
                            ['type' => 'text', 'text' => $variables['link'] ?? ''],
                        ],
                    ],
                ];

            case 'welcome':
                return [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $variables['user_name'] ?? ''],
                        ],
                    ],
                ];

            case 'submission_received':
                return [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $variables['challenge_title'] ?? ''],
                            ['type' => 'text', 'text' => $variables['submission_date'] ?? ''],
                        ],
                    ],
                ];

            case 'submission_approved':
            case 'submission_rejected':
                return [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $variables['challenge_title'] ?? ''],
                            ['type' => 'text', 'text' => $variables['feedback'] ?? ''],
                        ],
                    ],
                ];

            default:
                // Build generic components from variables
                $parameters = [];
                foreach ($variables as $value) {
                    if (is_string($value) || is_numeric($value)) {
                        $parameters[] = ['type' => 'text', 'text' => (string) $value];
                    }
                }
                return $parameters ? [['type' => 'body', 'parameters' => $parameters]] : [];
        }
    }

    /**
     * Send a simple text message (for testing/support)
     */
    public function sendTextMessage(string $to, string $text, ?User $user = null): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $text,
            ],
        ];

        return $this->sendApiRequest($payload, $user, 'text');
    }

    /**
     * Send an image message
     */
    public function sendImageMessage(string $to, string $imageUrl, ?string $caption = null, ?User $user = null): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'image',
            'image' => [
                'link' => $imageUrl,
            ],
        ];

        if ($caption) {
            $payload['image']['caption'] = $caption;
        }

        return $this->sendApiRequest($payload, $user, 'image');
    }

    /**
     * Send a document message
     */
    public function sendDocumentMessage(
        string $to,
        string $documentUrl,
        ?string $filename = null,
        ?string $caption = null,
        ?User $user = null
    ): array {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'document',
            'document' => [
                'link' => $documentUrl,
            ],
        ];

        if ($filename) {
            $payload['document']['filename'] = $filename;
        }

        if ($caption) {
            $payload['document']['caption'] = $caption;
        }

        return $this->sendApiRequest($payload, $user, 'document');
    }

    /**
     * Send interactive button message
     */
    public function sendButtonMessage(
        string $to,
        string $bodyText,
        array $buttons,
        ?string $headerText = null,
        ?string $footerText = null,
        ?User $user = null
    ): array {
        $formattedButtons = array_map(function ($button, $index) {
            return [
                'type' => 'reply',
                'reply' => [
                    'id' => $button['id'] ?? "btn_$index",
                    'title' => substr($button['title'], 0, 20),
                ],
            ];
        }, $buttons, array_keys($buttons));

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => $bodyText],
                'action' => ['buttons' => $formattedButtons],
            ],
        ];

        if ($headerText) {
            $payload['interactive']['header'] = ['type' => 'text', 'text' => $headerText];
        }

        if ($footerText) {
            $payload['interactive']['footer'] = ['text' => $footerText];
        }

        return $this->sendApiRequest($payload, $user, 'interactive_button');
    }

    /**
     * Send interactive list message
     */
    public function sendListMessage(
        string $to,
        string $bodyText,
        string $buttonText,
        array $sections,
        ?string $headerText = null,
        ?string $footerText = null,
        ?User $user = null
    ): array {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'body' => ['text' => $bodyText],
                'action' => [
                    'button' => substr($buttonText, 0, 20),
                    'sections' => $sections,
                ],
            ],
        ];

        if ($headerText) {
            $payload['interactive']['header'] = ['type' => 'text', 'text' => $headerText];
        }

        if ($footerText) {
            $payload['interactive']['footer'] = ['text' => $footerText];
        }

        return $this->sendApiRequest($payload, $user, 'interactive_list');
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(string $messageId): bool
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->post($this->getMessagesUrl(), [
                    'messaging_product' => 'whatsapp',
                    'status' => 'read',
                    'message_id' => $messageId,
                ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Failed to mark message as read', [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * React to a message with an emoji
     */
    public function reactToMessage(string $to, string $messageId, string $emoji): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
            'type' => 'reaction',
            'reaction' => [
                'message_id' => $messageId,
                'emoji' => $emoji,
            ],
        ];

        return $this->sendApiRequest($payload, null, 'reaction');
    }

    /**
     * Upload media to WhatsApp
     */
    public function uploadMedia(string $filePath, string $mimeType): ?string
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post($this->getMediaUploadUrl(), [
                    'messaging_product' => 'whatsapp',
                    'type' => $mimeType,
                ]);

            if ($response->successful()) {
                return $response->json('id');
            }

            Log::channel('whatsapp')->error('Media upload failed', ['response' => $response->json()]);
            return null;
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Media upload exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get media URL from media ID
     */
    public function getMediaUrl(string $mediaId): ?string
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/{$this->apiVersion}/{$mediaId}");

            if ($response->successful()) {
                return $response->json('url');
            }

            return null;
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Get media URL failed', [
                'media_id' => $mediaId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Download media content
     */
    public function downloadMedia(string $mediaUrl): ?string
    {
        try {
            $response = Http::withToken($this->accessToken)->get($mediaUrl);

            if ($response->successful()) {
                return $response->body();
            }

            return null;
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Media download failed', [
                'url' => $mediaUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get business profile information
     */
    public function getBusinessProfile(): ?array
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/whatsapp_business_profile", [
                    'fields' => 'about,address,description,email,profile_picture_url,websites,vertical',
                ]);

            if ($response->successful()) {
                return $response->json('data')[0] ?? null;
            }

            return null;
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Get business profile failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Update business profile
     */
    public function updateBusinessProfile(array $data): bool
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->post("{$this->baseUrl}/{$this->apiVersion}/{$this->phoneNumberId}/whatsapp_business_profile", [
                    'messaging_product' => 'whatsapp',
                    ...$data,
                ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Update business profile failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get all message templates
     */
    public function getMessageTemplates(): ?array
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/{$this->apiVersion}/{$this->businessAccountId}/message_templates");

            if ($response->successful()) {
                return $response->json('data');
            }

            return null;
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Get templates failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Send API request with logging and error handling
     */
    protected function sendApiRequest(array $payload, ?User $user, string $type): array
    {
        $result = [
            'success' => false,
            'message_id' => null,
            'error' => null,
        ];

        // Check rate limiting
        if (!$this->checkRateLimit()) {
            $result['error'] = 'Rate limit exceeded. Please try again later.';
            return $result;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->retry(3, 1000)
                ->post($this->getMessagesUrl(), $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['messages'][0]['id'])) {
                $result['success'] = true;
                $result['message_id'] = $responseData['messages'][0]['id'];

                // Log to database
                $this->logMessage($payload, $responseData, $user, $type);

                // Increment rate limit
                $this->incrementRateLimit();
            } else {
                $result['error'] = $responseData['error']['message'] ?? 'Unknown API error';
                Log::channel('whatsapp')->error('WhatsApp API error', [
                    'payload' => $payload,
                    'response' => $responseData,
                ]);
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            Log::channel('whatsapp')->error('WhatsApp exception', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
        }

        return $result;
    }

    /**
     * Log message to database
     */
    protected function logMessage(array $payload, array $response, ?User $user, string $type): void
    {
        try {
            WhatsAppMessage::create([
                'user_id' => $user?->id,
                'whatsapp_message_id' => $response['messages'][0]['id'] ?? null,
                'phone_number' => $payload['to'] ?? null,
                'direction' => 'outgoing',
                'type' => $type,
                'content' => $this->extractContent($payload),
                'payload' => $payload,
                'response' => $response,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::channel('whatsapp')->warning('Failed to log message', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Extract readable content from payload
     */
    protected function extractContent(array $payload): ?string
    {
        return match ($payload['type'] ?? null) {
            'text' => $payload['text']['body'] ?? null,
            'template' => 'Template: ' . ($payload['template']['name'] ?? 'unknown'),
            'image' => $payload['image']['caption'] ?? '[Image]',
            'document' => $payload['document']['caption'] ?? $payload['document']['filename'] ?? '[Document]',
            'interactive' => $payload['interactive']['body']['text'] ?? '[Interactive]',
            'reaction' => 'Reaction: ' . ($payload['reaction']['emoji'] ?? ''),
            default => null,
        };
    }

    /**
     * Format phone number to international format
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // If starts with +, remove it
        if (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }

        // If starts with 00, remove it
        if (str_starts_with($phone, '00')) {
            $phone = substr($phone, 2);
        }

        // If starts with 0 and is likely a local number, add Saudi Arabia code
        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            $phone = '966' . substr($phone, 1);
        }

        // If it's a 9-digit number, assume Saudi Arabia
        if (strlen($phone) === 9) {
            $phone = '966' . $phone;
        }

        return $phone;
    }

    /**
     * Check rate limiting
     */
    protected function checkRateLimit(): bool
    {
        $key = 'whatsapp_rate_limit_' . date('Y-m-d');
        $count = Cache::get($key, 0);
        $limit = config('whatsapp.rate_limit.messages_per_day', 1000);

        return $count < $limit;
    }

    /**
     * Increment rate limit counter
     */
    protected function incrementRateLimit(): void
    {
        $key = 'whatsapp_rate_limit_' . date('Y-m-d');
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, now()->endOfDay());
    }

    /**
     * Get rate limit status
     */
    public function getRateLimitStatus(): array
    {
        $key = 'whatsapp_rate_limit_' . date('Y-m-d');
        $count = Cache::get($key, 0);
        $limit = config('whatsapp.rate_limit.messages_per_day', 1000);

        return [
            'used' => $count,
            'limit' => $limit,
            'remaining' => max(0, $limit - $count),
            'percentage' => $limit > 0 ? round(($count / $limit) * 100, 2) : 0,
        ];
    }

    /**
     * Verify webhook signature from Meta
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $appSecret = config('whatsapp.app_secret') ?? config('services.meta.app_secret');
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process incoming webhook data
     */
    public function processWebhook(array $data): void
    {
        try {
            $entry = $data['entry'][0] ?? null;
            if (!$entry) return;

            $changes = $entry['changes'][0] ?? null;
            if (!$changes) return;

            $value = $changes['value'] ?? null;
            if (!$value) return;

            // Process messages
            if (isset($value['messages'])) {
                foreach ($value['messages'] as $message) {
                    $this->processIncomingMessage($message, $value['contacts'][0] ?? null);
                }
            }

            // Process status updates
            if (isset($value['statuses'])) {
                foreach ($value['statuses'] as $status) {
                    $this->processStatusUpdate($status);
                }
            }
        } catch (Exception $e) {
            Log::channel('whatsapp')->error('Webhook processing error', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Process incoming message
     */
    protected function processIncomingMessage(array $message, ?array $contact): void
    {
        $phone = $message['from'] ?? null;
        $messageId = $message['id'] ?? null;
        $type = $message['type'] ?? 'unknown';
        $timestamp = isset($message['timestamp']) ? date('Y-m-d H:i:s', $message['timestamp']) : now();

        $content = match ($type) {
            'text' => $message['text']['body'] ?? null,
            'image' => '[Image]',
            'document' => '[Document]',
            'audio' => '[Audio]',
            'video' => '[Video]',
            'location' => '[Location]',
            'contacts' => '[Contact]',
            'interactive' => $message['interactive']['button_reply']['title'] ?? $message['interactive']['list_reply']['title'] ?? '[Interactive]',
            default => null,
        };

        // Find user by phone number
        $user = User::where('whatsapp_number', 'like', '%' . substr($phone, -9))->first();

        // Log incoming message
        WhatsAppMessage::create([
            'user_id' => $user?->id,
            'whatsapp_message_id' => $messageId,
            'phone_number' => $phone,
            'direction' => 'incoming',
            'type' => $type,
            'content' => $content,
            'payload' => $message,
            'contact_name' => $contact['profile']['name'] ?? null,
            'status' => 'received',
            'received_at' => $timestamp,
        ]);

        // Mark as read
        if ($messageId) {
            $this->markAsRead($messageId);
        }

        // Fire event for further processing
        event(new \App\Events\WhatsAppMessageReceived($message, $user));
    }

    /**
     * Process message status update
     */
    protected function processStatusUpdate(array $status): void
    {
        $messageId = $status['id'] ?? null;
        $newStatus = $status['status'] ?? null;
        $timestamp = isset($status['timestamp']) ? date('Y-m-d H:i:s', $status['timestamp']) : now();

        if (!$messageId || !$newStatus) return;

        WhatsAppMessage::where('whatsapp_message_id', $messageId)
            ->update([
                'status' => $newStatus,
                'status_updated_at' => $timestamp,
            ]);
    }

    /**
     * Send welcome message to new user
     */
    public function sendWelcomeMessage(User $user): array
    {
        if (!$user->whatsapp_number) {
            return ['success' => false, 'error' => 'User has no WhatsApp number'];
        }

        return $this->sendMessage($user, 'welcome', [
            'user_name' => $user->name,
        ]);
    }

    /**
     * Send challenge notification
     */
    public function sendChallengeNotification(User $user, string $challengeTitle, string $link): array
    {
        if (!$user->whatsapp_number || !$user->hasWhatsAppEnabled()) {
            return ['success' => false, 'error' => 'WhatsApp not enabled for user'];
        }

        return $this->sendMessage($user, 'critical_update', [
            'challenge_title' => $challengeTitle,
            'update_message' => __('A new challenge is available for you!'),
            'link' => $link,
        ]);
    }

    /**
     * Check if service is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->phoneNumberId);
    }

    /**
     * Get service status
     */
    public function getStatus(): array
    {
        return [
            'configured' => $this->isConfigured(),
            'rate_limit' => $this->getRateLimitStatus(),
            'api_version' => $this->apiVersion,
        ];
    }
}
