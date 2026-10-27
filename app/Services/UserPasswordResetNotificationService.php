<?php

namespace App\Services;

use App\Mail\UserPasswordResetMail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UserPasswordResetNotificationService
{
    /**
     * Send password reset credentials via SMS and/or Email.
     *
     * @param User $user
     * @param string $plainPassword
     * @param Profile|null $profile
     * @return array
     */
    public static function sendResetCredentials(User $user, string $plainPassword, ?Profile $profile = null): array
    {
        $profile = $profile ?? $user->profile;
        $email = $profile?->email;
        $phone = $profile?->phone;

        $smsSent = false;
        $emailSent = false;

        // 1. Dispatch SMS Credentials via JioCX Gateway
        if (!empty($phone)) {
            try {
                $sms = app(SmsService::class);
                $smsResult = $sms->sendCredentials($phone, $user->username, $plainPassword);
                if (!empty($smsResult['success'])) {
                    Log::info("[UserPasswordReset] SMS reset credentials sent to '{$phone}' for user '{$user->username}'.");
                    $smsSent = true;
                } else {
                    Log::warning("[UserPasswordReset] SMS reset dispatch returned unsuccessful for '{$phone}' ({$user->username}).", ['result' => $smsResult]);
                }
            } catch (Throwable $e) {
                Log::warning("[UserPasswordReset] Exception sending SMS to '{$phone}' for user '{$user->username}': " . $e->getMessage());
            }
        }

        // 2. Dispatch Password Reset Email
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($email)->send(new UserPasswordResetMail($user, $plainPassword, $profile));
                Log::info("[UserPasswordReset] Reset email dispatched successfully to '{$email}' for user '{$user->username}'.");
                $emailSent = true;
            } catch (Throwable $e) {
                Log::warning("[UserPasswordReset] Exception sending reset email to '{$email}' for user '{$user->username}': " . $e->getMessage());
            }
        }

        return [
            'sms_sent' => $smsSent,
            'email_sent' => $emailSent,
            'has_phone' => !empty($phone),
            'has_email' => !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL),
            'masked_phone' => self::maskPhone($phone),
            'masked_email' => self::maskEmail($email),
        ];
    }

    /**
     * Mask a phone number for secure public feedback (e.g. 98******10).
     */
    public static function maskPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) < 4) {
            return $clean;
        }

        return substr($clean, 0, 2) . '******' . substr($clean, -2);
    }

    /**
     * Mask an email address for secure public feedback (e.g. jo****@vignan.ac.in).
     */
    public static function maskEmail(?string $email): ?string
    {
        if (empty($email) || !str_contains($email, '@')) {
            return null;
        }

        [$name, $domain] = explode('@', $email, 2);
        if (strlen($name) <= 2) {
            $maskedName = substr($name, 0, 1) . '****';
        } else {
            $maskedName = substr($name, 0, 2) . '****';
        }

        return $maskedName . '@' . $domain;
    }
}
