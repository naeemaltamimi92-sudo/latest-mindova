<?php

namespace App\Services;

use App\Models\User;
use Twilio\Rest\Client;
use Exception;

/**
 * Twilio WhatsApp Business API (Production) Service
 *
 * This service will be used once you get Meta/Twilio approval for production WhatsApp.
 * Currently disabled - enable by setting TWILIO_WHATSAPP_PRODUCTION_ENABLED=true
 */
class WhatsAppProductionService
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
     * Send WhatsApp message using approved Content Template.
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

        // Get approved Content SID for template
        $contentSid = $this->getContentSid($templateName);

        // Map variables to template format
        $contentVariables = $this->mapVariables($templateName, $variables);

        // Send via Twilio using Content Template API
        $message = $this->twilioClient->messages->create(
            'whatsapp:' . $user->whatsapp_number,
            [
                'from' => 'whatsapp:' . $this->fromWhatsApp,
                'contentSid' => $contentSid,
                'contentVariables' => json_encode($contentVariables),
            ]
        );

        return $message->sid;
    }

    /**
     * Get Twilio Content SID for template name.
     *
     * TODO: Update these SIDs after creating templates in Twilio Console
     *
     * @param string $templateName
     * @return string
     * @throws Exception
     */
    private function getContentSid(string $templateName): string
    {
        $contentSids = [
            // TODO: Replace with actual Content SIDs from Twilio Console after template approval
            'team_invite' => env('TWILIO_CONTENT_SID_TEAM_INVITE', 'HXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
            'task_assigned' => env('TWILIO_CONTENT_SID_TASK_ASSIGNED', 'HXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
            'critical_update' => env('TWILIO_CONTENT_SID_CRITICAL_UPDATE', 'HXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'),
        ];

        if (!isset($contentSids[$templateName])) {
            throw new Exception("Content template not found: {$templateName}");
        }

        $sid = $contentSids[$templateName];

        if (str_starts_with($sid, 'HXXXXXXX')) {
            throw new Exception("Content SID not configured for template: {$templateName}. Please create template in Twilio Console and update .env");
        }

        return $sid;
    }

    /**
     * Map variables to Twilio Content Template format.
     *
     * @param string $templateName
     * @param array $variables
     * @return array
     */
    private function mapVariables(string $templateName, array $variables): array
    {
        // Map to WhatsApp template variable format ({{1}}, {{2}}, etc.)
        switch ($templateName) {
            case 'team_invite':
                return [
                    '1' => $variables['challenge_title'],
                    '2' => $variables['link'],
                ];

            case 'task_assigned':
                return [
                    '1' => $variables['task_title'],
                    '2' => $variables['link'],
                ];

            case 'critical_update':
                return [
                    '1' => $variables['challenge_title'],
                    '2' => $variables['link'],
                ];

            default:
                return $variables;
        }
    }
}
