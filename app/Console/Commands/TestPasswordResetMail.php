<?php

namespace App\Console\Commands;

use App\Mail\UserPasswordResetMail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestPasswordResetMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-password-reset {email? : Destination email address (defaults to ADMIN_ALERT_EMAIL or web@vignan.ac.in)} {--username=211FA04001 : Sample Username/Reg No}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an example password reset email template to test mail configuration and delivery';

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

        $this->info("Preparing sample password reset email template...");
        $this->line("• Recipient: {$targetEmail}");
        $this->line("• Username:  {$sampleUsername}");
        $this->line("• Temporary: {$samplePassword}");
        $this->line("• Mailer:    " . config('mail.default'));
        $this->line("• Host:      " . config('mail.mailers.smtp.host') . ":" . config('mail.mailers.smtp.port'));

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
            Mail::to($targetEmail)->send(new UserPasswordResetMail($user, $samplePassword, $profile));
            $this->info("✔ Example password reset email template dispatched successfully to {$targetEmail}!");
            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->error("✖ Failed to dispatch example email: " . $e->getMessage());
            $this->line($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
