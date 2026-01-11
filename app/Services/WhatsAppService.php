<?php

namespace App\Services;

use App\Models\User;
use App\Models\WhatsAppNotification;
use Twilio\Rest\Client;
use Exception;

class WhatsAppService
{
    private Client $twilioClient;
    private string $fromWhatsApp;

    public function __construct()
    {
        $this->twilioClient = new Client(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
        $this->fromWhatsApp = config('services.twilio.whatsapp_from');
    }

    /**
     * Send a WhatsApp message using approved template.
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

        // Get template content
        $content = $this->getTemplateContent($templateName, $variables);

        // Send via Twilio
        $message = $this->twilioClient->messages->create(
            'whatsapp:' . $user->whatsapp_number,
            [
                'from' => 'whatsapp:' . $this->fromWhatsApp,
                'body' => $content,
            ]
        );

        return $message->sid;
    }

    /**
     * Get template content with variables replaced.
     *
     * @param string $templateName
     * @param array $variables
     * @return string
     */
    private function getTemplateContent(string $templateName, array $variables): string
    {
        $templates = [
            'team_invite' => "Mindova Notification\n\nYou have been invited to join a micro-team for the challenge \"{{challenge_title}}\".\n\nView details: {{link}}",

            'task_assigned' => "Mindova Notification\n\nA new task has been assigned to you: \"{{task_title}}\".\n\nView details: {{link}}",

            'critical_update' => "Mindova Notification\n\nCritical update on challenge \"{{challenge_title}}\":\n{{update_message}}\n\nView details: {{link}}",
        ];

        if (!isset($templates[$templateName])) {
            throw new Exception("Template {$templateName} not found");
        }

        $content = $templates[$templateName];

        // Replace variables
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }

    /**
     * Validate phone number format (E.164).
     *
     * @param string $phoneNumber
     * @return bool
     */
    public static function validatePhoneNumber(string $phoneNumber): bool
    {
        // E.164 format: +[country code][number]
        // Example: +966501234567 (Saudi Arabia)
        return preg_match('/^\+[1-9]\d{1,14}$/', $phoneNumber) === 1;
    }

    /**
     * Format phone number to E.164 if needed.
     *
     * @param string $phoneNumber
     * @param string $defaultCountryCode Default country code (e.g., '966' for Saudi Arabia)
     * @return string
     */
    public static function formatPhoneNumber(string $phoneNumber, string $defaultCountryCode = '966'): string
    {
        // Remove all non-digit characters except +
        $phoneNumber = preg_replace('/[^\d+]/', '', $phoneNumber);

        // If already in E.164 format, return as is
        if (str_starts_with($phoneNumber, '+')) {
            return $phoneNumber;
        }

        // If starts with 00, replace with +
        if (str_starts_with($phoneNumber, '00')) {
            return '+' . substr($phoneNumber, 2);
        }

        // If starts with 0, remove it and add country code
        if (str_starts_with($phoneNumber, '0')) {
            return '+' . $defaultCountryCode . substr($phoneNumber, 1);
        }

        // Otherwise, add + and country code
        return '+' . $defaultCountryCode . $phoneNumber;
    }
}
