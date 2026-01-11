<?php

namespace App\Console\Commands;

use App\Services\WhatsAppCloudService;
use Illuminate\Console\Command;

class WhatsAppTestCommand extends Command
{
    protected $signature = 'whatsapp:test
                            {--phone= : Phone number to send test message to}
                            {--message= : Custom message to send}
                            {--status : Check service status only}';

    protected $description = 'Test WhatsApp integration and send test messages';

    public function handle(WhatsAppCloudService $whatsAppService): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║     WhatsApp Integration Test Tool       ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        // Check configuration
        if (!$whatsAppService->isConfigured()) {
            $this->error('❌ WhatsApp is NOT configured!');
            $this->warn('');
            $this->warn('Missing environment variables. Please set:');
            $this->line('  • WHATSAPP_ACCESS_TOKEN');
            $this->line('  • WHATSAPP_PHONE_NUMBER_ID');
            $this->warn('');
            $this->info('See docs/WHATSAPP_SETUP_GUIDE.md for instructions.');
            return Command::FAILURE;
        }

        $this->info('✅ WhatsApp is configured');
        $this->info('');

        // Get status
        $status = $whatsAppService->getStatus();

        $this->table(
            ['Setting', 'Value'],
            [
                ['API Version', $status['api_version']],
                ['Configured', $status['configured'] ? 'Yes' : 'No'],
                ['Messages Today', $status['rate_limit']['used']],
                ['Daily Limit', $status['rate_limit']['limit']],
                ['Remaining', $status['rate_limit']['remaining']],
            ]
        );

        if ($this->option('status')) {
            return Command::SUCCESS;
        }

        // Send test message
        $phone = $this->option('phone');

        if (!$phone) {
            $phone = $this->ask('Enter phone number to send test message (e.g., 966501234567)');
        }

        if (!$phone) {
            $this->warn('No phone number provided. Skipping message test.');
            return Command::SUCCESS;
        }

        $message = $this->option('message') ?? 'Hello from Mindova! 🎉 This is a test message from your WhatsApp integration.';

        $this->info('');
        $this->info("Sending test message to: {$phone}");
        $this->info("Message: {$message}");
        $this->info('');

        $result = $whatsAppService->sendTextMessage($phone, $message);

        if ($result['success']) {
            $this->info('✅ Message sent successfully!');
            $this->info("   Message ID: {$result['message_id']}");
        } else {
            $this->error('❌ Failed to send message');
            $this->error("   Error: {$result['error']}");
            return Command::FAILURE;
        }

        $this->info('');
        return Command::SUCCESS;
    }
}
