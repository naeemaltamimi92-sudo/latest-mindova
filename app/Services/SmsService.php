<?php

namespace App\Services;

use App\Models\User;
use Twilio\Rest\Client;
use Exception;

class SmsService
{
    private Client $twilioClient;
    private string $fromNumber;

    public function __construct()
    {
        $this->twilioClient = new Client(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
        $this->fromNumber = config('services.twilio.sms_from');
    }

    /**
     * Send an SMS message.
     *
     * @param User $user
     * @param string $templateName
     * @param array $variables
     * @return string Provider message ID
     * @throws Exception
     */
    public function sendMessage(User $user, string $templateName, array $variables): string
    {
        // Validate user has a phone number
        if (empty($user->whatsapp_number)) {
            throw new Exception('User has no phone number configured');
        }

        // Get template content
        $content = $this->getTemplateContent($templateName, $variables);

        // Send via Twilio SMS
        $message = $this->twilioClient->messages->create(
            $user->whatsapp_number, // Using same phone number field
            [
                'from' => $this->fromNumber,
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
        return preg_match('/^\+[1-9]\d{1,14}$/', $phoneNumber) === 1;
    }
}
