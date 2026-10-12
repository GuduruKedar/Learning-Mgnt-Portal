<?php

namespace App\Console\Commands;

use App\Services\DiscordWebhookService;
use Illuminate\Console\Command;

class TestDiscordWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:test-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test ping alert to the configured Discord webhook channel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing Discord Webhook Connection...');

        $webhookUrl = config('discord_webhook.webhook_url');
        if (empty($webhookUrl)) {
            $this->error('DISCORD_ERROR_WEBHOOK_URL is not configured in your .env file.');
            $this->line('Add DISCORD_ERROR_WEBHOOK_URL="https://discord.com/api/webhooks/..." to your .env file.');
            return Command::FAILURE;
        }

        $result = DiscordWebhookService::sendTestPing();

        if ($result['success']) {
            $this->info('✔ ' . $result['message']);
            return Command::SUCCESS;
        }

        $this->error('✖ ' . $result['message']);
        return Command::FAILURE;
    }
}
