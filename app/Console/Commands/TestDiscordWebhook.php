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
        $this->info('Notice: Error alerts have been transitioned from Discord to Email Alerting (web@vignan.ac.in).');
        $this->line('Testing Error Alert Email Dispatch...');

        $result = \App\Services\ErrorMailAlertService::sendTestPing();

        if ($result['success']) {
            $this->info('✔ ' . $result['message']);
            return Command::SUCCESS;
        }

        $this->error('✖ ' . $result['message']);
        return Command::FAILURE;
    }
}
