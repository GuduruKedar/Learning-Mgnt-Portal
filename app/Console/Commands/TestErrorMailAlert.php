<?php

namespace App\Console\Commands;

use App\Services\ErrorMailAlertService;
use Illuminate\Console\Command;

class TestErrorMailAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'error:test-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test error alert email to the administrator (web@vignan.ac.in)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing Error Alert Email Dispatch to web@vignan.ac.in...');

        $result = ErrorMailAlertService::sendTestPing();

        if ($result['success']) {
            $this->info('✔ ' . $result['message']);
            return Command::SUCCESS;
        }

        $this->error('✖ ' . $result['message']);
        return Command::FAILURE;
    }
}
