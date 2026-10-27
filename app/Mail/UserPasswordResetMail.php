<?php

namespace App\Mail;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $temporaryPassword;
    public ?Profile $profile;
    public string $loginUrl;
    public string $changePasswordUrl;
    public string $resetTime;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $temporaryPassword, ?Profile $profile = null)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->profile = $profile ?? $user->profile;
        $this->loginUrl = route('login');
        $this->changePasswordUrl = route('password.edit');
        $this->resetTime = now()->format('d M Y, h:i A') . ' IST';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $fullName = trim(($this->profile?->first_name ?? '') . ' ' . ($this->profile?->last_name ?? ''));
        $displayName = !empty($fullName) ? $fullName : $this->user->username;

        return $this->subject('[LMS Security] Your Account Password Has Been Reset - New Temporary Password')
                    ->view('emails.password_reset')
                    ->with([
                        'user' => $this->user,
                        'profile' => $this->profile,
                        'displayName' => $displayName,
                        'temporaryPassword' => $this->temporaryPassword,
                        'loginUrl' => $this->loginUrl,
                        'changePasswordUrl' => $this->changePasswordUrl,
                        'resetTime' => $this->resetTime,
                    ]);
    }
}
