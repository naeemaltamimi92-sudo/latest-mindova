<?php

namespace App\Services;

use App\Models\User;
use App\Models\WhatsAppNotification;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Unified notification service supporting multiple channels with fallback.
 *
 * Supported channels (in priority order):
 * 1. WhatsApp Production (Twilio Business API)
 * 2. WhatsApp Cloud API (Meta direct)
 * 3. SMS (Twilio)
 * 4. WhatsApp Sandbox (testing only)
 */
class NotificationChannelService
{
    /**
     * Send notification with automatic channel selection and fallback.
     *
     * @param User $user
     * @param string $templateName
     * @param array $variables
     * @param string|null $preferredChannel Force specific channel: 'whatsapp_production', 'whatsapp_cloud', 'sms', 'whatsapp_sandbox'
     * @return array ['success' => bool, 'channel' => string, 'message_id' => string|null, 'error' => string|null]
     */
    public static function send(User $user, string $templateName, array $variables, ?string $preferredChannel = null): array
    {
        // Validate user has opted in
        if (!$user->hasWhatsAppEnabled()) {
            return [
                'success' => false,
                'channel' => null,
                'message_id' => null,
                'error' => 'User has not opted in for notifications',
            ];
        }

        // Define channel priority (can be configured in .env)
        $channelPriority = $preferredChannel
            ? [$preferredChannel]
            : self::getChannelPriority();

        $lastError = null;

        // Try each channel in priority order
        foreach ($channelPriority as $channel) {
            try {
                $messageId = self::sendViaChannel($channel, $user, $templateName, $variables);

                Log::info("Notification sent successfully", [
                    'user_id' => $user->id,
                    'channel' => $channel,
                    'template' => $templateName,
                    'message_id' => $messageId,
                ]);

                return [
                    'success' => true,
                    'channel' => $channel,
                    'message_id' => $messageId,
                    'error' => null,
                ];
            } catch (Exception $e) {
                $lastError = $e->getMessage();

                Log::warning("Notification failed on channel, trying next", [
                    'user_id' => $user->id,
                    'channel' => $channel,
                    'template' => $templateName,
                    'error' => $lastError,
                ]);

                // Continue to next channel
                continue;
            }
        }

        // All channels failed
        Log::error("Notification failed on all channels", [
            'user_id' => $user->id,
            'template' => $templateName,
            'last_error' => $lastError,
        ]);

        return [
            'success' => false,
            'channel' => null,
            'message_id' => null,
            'error' => "All channels failed. Last error: {$lastError}",
        ];
    }

    /**
     * Send via specific channel.
     *
     * @param string $channel
     * @param User $user
     * @param string $templateName
     * @param array $variables
     * @return string Message ID
     * @throws Exception
     */
    private static function sendViaChannel(string $channel, User $user, string $templateName, array $variables): string
    {
        switch ($channel) {
            case 'whatsapp_production':
                return self::sendViaWhatsAppProduction($user, $templateName, $variables);

            case 'whatsapp_cloud':
                return self::sendViaWhatsAppCloud($user, $templateName, $variables);

            case 'sms':
                return self::sendViaSms($user, $templateName, $variables);

            case 'whatsapp_sandbox':
                return self::sendViaWhatsAppSandbox($user, $templateName, $variables);

            default:
                throw new Exception("Unknown channel: {$channel}");
        }
    }

    /**
     * Send via Twilio WhatsApp Production (approved templates).
     */
    private static function sendViaWhatsAppProduction(User $user, string $templateName, array $variables): string
    {
        // Check if production is enabled
        if (!config('services.twilio.whatsapp_production_enabled')) {
            throw new Exception('WhatsApp Production not enabled');
        }

        $service = new WhatsAppProductionService();
        return $service->sendMessage($user, $templateName, $variables);
    }

    /**
     * Send via Meta WhatsApp Cloud API.
     */
    private static function sendViaWhatsAppCloud(User $user, string $templateName, array $variables): string
    {
        // Check if Meta Cloud API is enabled
        if (!config('services.meta.whatsapp_cloud_enabled')) {
            throw new Exception('WhatsApp Cloud API not enabled');
        }

        $service = new WhatsAppCloudService();
        return $service->sendMessage($user, $templateName, $variables);
    }

    /**
     * Send via Twilio SMS.
     */
    private static function sendViaSms(User $user, string $templateName, array $variables): string
    {
        // Check if SMS is enabled
        if (!config('services.twilio.sms_enabled', true)) {
            throw new Exception('SMS not enabled');
        }

        $service = new SmsService();
        return $service->sendMessage($user, $templateName, $variables);
    }

    /**
     * Send via Twilio WhatsApp Sandbox (testing).
     */
    private static function sendViaWhatsAppSandbox(User $user, string $templateName, array $variables): string
    {
        $service = new WhatsAppService();
        return $service->sendMessage($user, $templateName, $variables);
    }

    /**
     * Get channel priority from config.
     *
     * @return array
     */
    private static function getChannelPriority(): array
    {
        $priority = config('services.notification_channel_priority');

        if (!$priority) {
            // Default priority
            return [
                'whatsapp_production', // Try production first
                'sms',                 // Fall back to SMS (works immediately)
                'whatsapp_sandbox',    // Last resort: sandbox (requires recipient join)
            ];
        }

        return $priority;
    }

    /**
     * Get available channels based on configuration.
     *
     * @return array
     */
    public static function getAvailableChannels(): array
    {
        $channels = [];

        if (config('services.twilio.whatsapp_production_enabled')) {
            $channels[] = 'whatsapp_production';
        }

        if (config('services.meta.whatsapp_cloud_enabled')) {
            $channels[] = 'whatsapp_cloud';
        }

        if (config('services.twilio.sms_enabled', true)) {
            $channels[] = 'sms';
        }

        // Sandbox always available
        $channels[] = 'whatsapp_sandbox';

        return $channels;
    }

    /**
     * Check if a specific channel is available.
     *
     * @param string $channel
     * @return bool
     */
    public static function isChannelAvailable(string $channel): bool
    {
        return in_array($channel, self::getAvailableChannels());
    }
}
