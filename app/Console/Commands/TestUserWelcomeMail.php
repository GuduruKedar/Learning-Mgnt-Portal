<?php

namespace App\Console\Commands;

use App\Mail\UserWelcomeMail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestUserWelcomeMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-welcome {email? : Destination email address (defaults to ADMIN_ALERT_EMAIL or web@vignan.ac.in)} {--username=221FA04001 : Sample Username/Reg No}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an example welcome credentials email template to test mail configuration and delivery';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $targetEmail = $this->argument('email') 
            ?: config('services.admin_alert.email', env('ADMIN_ALERT_EMAIL', 'web@vignan.ac.in'));

        $sampleUsername = (string) $this->option('username');
        $samplePassword = 'Student#' . rand(1000, 9999);

        $this->info("Preparing sample welcome email template...");
        $this->line("• Recipient: {$targetEmail}");
        $this->line("• Username:  {$sampleUsername}");
        $this->line("• Temporary: {$samplePassword}");
        $this->line("• Mailer:    " . config('mail.default'));

        $user = new User([
            'username' => $sampleUsername,
        ]);

        $profile = new Profile([
            'first_name' => 'Sample',
            'last_name' => 'Student',
            'email' => $targetEmail,
            'phone' => '9440151948',
            'designation' => 'Student (CSE)',
        ]);

        try {
            Mail::to($targetEmail)->send(new UserWelcomeMail($user, $samplePassword, $profile));
            $this->info("✔ Example welcome email template dispatched successfully to {$targetEmail}!");
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->error("✖ Failed to dispatch example email: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
