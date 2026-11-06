<?php

namespace App\Services;

use App\Mail\UserWelcomeMail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UserWelcomeNotificationService
{
    /**
     * Determine if a user or profile belongs to the student role.
     */
    public static function isStudent(User $user, ?Profile $profile = null): bool
    {
        $role = strtolower((string)($profile?->roles_id ?? $user->profile?->roles_id ?? $user->role ?? ''));
        return in_array($role, ['stu', 'student', '4', '6']);
    }

    /**
     * Send welcome email and SMS credentials with password change instruction.
     *
     * @param User $user
     * @param string $plainPassword
     * @param Profile|null $profile
     * @return bool
     */
    public static function sendWelcomeCredentials(User $user, string $plainPassword, ?Profile $profile = null): bool
    {
        $result = self::sendWelcomeCredentialsResult($user, $plainPassword, $profile);
        return $result['sms_sent'] || $result['email_sent'];
    }

    /**
     * Send welcome credentials and return detailed delivery result per channel.
     * Note: SMTP mail is stopped for student role; only SMS is dispatched for students.
     *
     * @param User $user
     * @param string $plainPassword
     * @param Profile|null $profile
     * @return array
     */
    public static function sendWelcomeCredentialsResult(User $user, string $plainPassword, ?Profile $profile = null): array
    {
        $profile = $profile ?? $user->profile;
        $email = $profile?->email;
        $phone = $profile?->phone;
        $isStudent = self::isStudent($user, $profile);

        $smsSent = false;
        $emailSent = false;

        // 1. Dispatch SMS Credentials via JioCX Gateway (Sent to all roles including students)
        if (!empty($phone)) {
            try {
                $sms = app(SmsService::class);
                $smsResult = $sms->sendCredentials($phone, $user->username, $plainPassword);
                if (!empty($smsResult['success'])) {
                    Log::info("[UserWelcomeNotification] SMS credentials sent successfully to '{$phone}' for user '{$user->username}'.");
                    $smsSent = true;
                } else {
                    Log::warning("[UserWelcomeNotification] SMS credentials dispatch failed for '{$phone}' ({$user->username}).", ['result' => $smsResult]);
                }
            } catch (Throwable $e) {
                Log::warning("[UserWelcomeNotification] Exception sending SMS to '{$phone}' for user '{$user->username}': " . $e->getMessage());
            }
        } else {
            Log::info("[UserWelcomeNotification] No phone number available for SMS delivery for user '{$user->username}'.");
        }

        // 2. Dispatch Welcome Email via SMTP (STOPPED for student role - only for staff/admin/coordinator)
        if ($isStudent) {
            Log::info("[UserWelcomeMail] Skipped SMTP welcome email for student '{$user->username}': SMTP mail is stopped for students role.");
        } elseif (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($email)->send(new UserWelcomeMail($user, $plainPassword, $profile));
                Log::info("[UserWelcomeMail] Welcome email sent successfully to '{$email}' for user '{$user->username}'.");
                $emailSent = true;
            } catch (Throwable $e) {
                Log::warning("[UserWelcomeMail] Failed sending email to '{$email}' for user '{$user->username}': " . $e->getMessage());
            }
        } else {
            Log::info("[UserWelcomeMail] Skipped email for user '{$user->username}': No valid email address provided.");
        }

        return [
            'sms_sent' => $smsSent,
            'email_sent' => $emailSent,
            'is_student' => $isStudent,
            'has_phone' => !empty($phone),
            'has_email' => !$isStudent && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL),
        ];
    }
}
