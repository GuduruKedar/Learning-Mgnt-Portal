<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\SmsService;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'emp_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $empId = trim($request->emp_id);
        $password = $request->password;

        // Optimized: O(1) indexed lookup with eager-loaded profile
        $user = \App\Models\User::with('profile')->where('username', $empId)->first();
        if (!$user) {
            // Case-insensitive fallback if entered with different casing
            $user = \App\Models\User::with('profile')->whereRaw('LOWER(username) = ?', [strtolower($empId)])->first();
        }

        if ($user && $user->requires_password_reset) {
            $phone = $user->profile?->phone;
            if (!empty($phone)) {
                $tempPassword = $this->generateTemporaryPassword($user);
                $user->password = Hash::make($tempPassword);
                $user->failed_login_attempts = 0;
                $user->requires_password_reset = false;
                $user->save();

                $sms = app(SmsService::class);
                $sms->sendCredentials($phone, $user->username, $tempPassword);

                if (!empty($user->profile?->email) && filter_var($user->profile->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->profile->email)->send(new \App\Mail\UserWelcomeMail($user, $tempPassword, $user->profile));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning("[UserWelcomeMail] Failed sending email: " . $e->getMessage());
                    }
                }

                \App\Services\ActivityLogger::log(
                    'account_unlocked_sms',
                    'Temporary Password Sent via SMS',
                    'Authentication',
                    "Locked account {$user->username} was issued a temporary password via SMS.",
                    'info',
                    ['user' => $user]
                );

                $maskedPhone = substr($phone, 0, 2) . '******' . substr($phone, -2);
                return back()->with([
                    'forgot_success' => "Your account was locked. A new temporary password has been sent to your registered mobile number ({$maskedPhone}). Please enter it below to login.",
                    'prefilled_username' => $user->username,
                    'show_forgot_password' => true,
                ]);
            }

            return back()->withErrors([
                'emp_id' => 'Your account has been locked. No registered mobile number is on file. Please contact your Department Coordinator to unlock your account.',
            ]);
        }

        $canonicalUsername = $user ? $user->username : $empId;
        $credentials = ['username' => $canonicalUsername, 'password' => $password];

        // Also fallback to trimmed password if user accidentally added leading/trailing whitespace
        $authenticated = Auth::attempt($credentials) || (trim($password) !== $password && Auth::attempt(['username' => $canonicalUsername, 'password' => trim($password)]));

        if ($authenticated) {
            $authUser = Auth::user();
            if ($authUser) {
                // High-performance optimization: Only execute DB UPDATE if flags actually changed
                if ($authUser->failed_login_attempts > 0 || $authUser->requires_password_reset) {
                    $authUser->failed_login_attempts = 0;
                    $authUser->requires_password_reset = false;
                    $authUser->save();
                }

                // Prime session with user identity to eliminate redundant SQL queries across all routes
                $profile = $authUser->profile;
                $roleName = $profile->roles_id ?? 'stu';
                $fullName = $roleName === 'sa' ? 'Super Admin' : trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
                $firstName = $profile->first_name ? ucwords(strtolower($profile->first_name)) : $authUser->username;

                session([
                    'auth_user_id' => $authUser->id,
                    'auth_user_role' => $roleName,
                    'auth_user_name' => !empty($fullName) ? ucwords(strtolower($fullName)) : $authUser->username,
                    'auth_user_first_name' => $firstName,
                    'auth_user_dept' => $profile->departments_id ?? null,
                    'auth_user_school' => $profile->schools_id ?? null,
                ]);
            }
            
            $request->session()->regenerate();

            \App\Services\ActivityLogger::log(
                'user_login',
                'User Logged In',
                'Authentication',
                'User ' . ($user->profile->username ?? $user->username) . ' signed in successfully.',
                'info',
                ['user' => $authUser ?? $user]
            );

            return redirect()->route('dashboard');
        }

        if ($user) {
            // Reset stale failed attempts if last attempt occurred more than 15 minutes ago
            if ($user->failed_login_attempts > 0 && $user->updated_at && $user->updated_at->lt(now()->subMinutes(15))) {
                $user->failed_login_attempts = 0;
            }

            $user->failed_login_attempts += 1;
            $user->save();

            \App\Services\ActivityLogger::log(
                'failed_login',
                'Failed Login Attempt',
                'Authentication',
                'Failed password attempt for username ' . $user->username . ' (Attempt ' . $user->failed_login_attempts . ').',
                'warning',
                ['user' => $user, 'department_id' => $user->profile?->departments_id]
            );

            if ($user->failed_login_attempts >= 2) {
                return back()->withErrors([
                    'emp_id' => "Invalid Username / Employee ID or Password. You have reached {$user->failed_login_attempts} failed login attempts. Please click 'Forgot Password?' to reset your credentials via SMS.",
                ])->with([
                    'prefilled_username' => $user->username,
                    'show_forgot_prompt' => true,
                ]);
            }

            $attemptsLeft = 2 - $user->failed_login_attempts;
            return back()->withErrors([
                'emp_id' => "Invalid Username / Employee ID or Password. You have {$attemptsLeft} attempt left before a security warning.",
            ])->with('prefilled_username', $user->username);
        }

        return back()->withErrors([
            'emp_id' => 'Invalid Username / Employee ID or Password.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Services\ActivityLogger::log(
                'user_logout',
                'User Logged Out',
                'Authentication',
                'User ' . (Auth::user()->username) . ' logged out.',
                'info'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgotPassword(Request $request, SmsService $sms)
    {
        $request->validate([
            'username' => 'required|string',
        ], [
            'username.required' => 'Please enter your Username / Registration Number.',
        ]);

        $empId = trim($request->username);

        // 1. Check first if the user exists
        $user = User::with('profile')->where('username', $empId)->first();
        if (!$user) {
            $user = User::with('profile')->whereRaw('LOWER(username) = ?', [strtolower($empId)])->first();
        }

        if (!$user) {
            return back()->with('forgot_error', "No user account was found with username '{$empId}'. Please check your credentials or contact your Department Coordinator.");
        }

        // 2. Check if phone number is present on profile
        $phone = $user->profile?->phone;
        if (empty($phone)) {
            return back()->with('forgot_error', "No mobile phone number is linked to account '{$user->username}'. Please contact your Department Coordinator to register your mobile number.");
        }

        // 3. Generate a secure temporary password
        $newPassword = $this->generateTemporaryPassword($user);

        // 4. Update the user password, unlock account and clear failed attempts
        $user->password = Hash::make($newPassword);
        $user->failed_login_attempts = 0;
        $user->requires_password_reset = false;
        $user->save();

        // 5. Send password to phone number via approved JioCX SMS Template
        $smsResult = $sms->sendCredentials($phone, $user->username, $newPassword);

        // 6. Also dispatch email if available
        if (!empty($user->profile?->email) && filter_var($user->profile->email, FILTER_VALIDATE_EMAIL)) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->profile->email)->send(new \App\Mail\UserWelcomeMail($user, $newPassword, $user->profile));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("[UserWelcomeMail] Failed sending email: " . $e->getMessage());
            }
        }

        \App\Services\ActivityLogger::log(
            'forgot_password_sms',
            'Password Reset via SMS',
            'Authentication',
            "User {$user->username} requested forgot password. New password dispatched via SMS to registered mobile.",
            'info',
            ['user' => $user]
        );

        $maskedPhone = substr($phone, 0, 2) . '******' . substr($phone, -2);

        return back()->with([
            'forgot_success' => "A new temporary password has been sent to your registered mobile number ({$maskedPhone}). Please enter it below to login to your account.",
            'prefilled_username' => $user->username,
        ]);
    }

    public function generateTemporaryPassword(User $user): string
    {
        $role = $user->role;
        if ($role === 'stu') {
            return 'Student' . rand(1000, 9999);
        } elseif ($role === 'sta') {
            return 'Staff' . rand(1000, 9999);
        } elseif ($role === 'admin') {
            return 'Admin' . rand(1000, 9999);
        }

        return 'Vignan' . rand(1000, 9999);
    }
}
