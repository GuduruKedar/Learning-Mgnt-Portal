<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $user = \App\Models\User::where('username', $empId)
            ->orWhereRaw('LOWER(TRIM(username)) = ?', [strtolower($empId)])
            ->first();

        if ($user && $user->requires_password_reset) {
            return back()->withErrors([
                'emp_id' => 'Your account has been locked due to too many failed attempts. Please contact an administrator to reset your password.',
            ]);
        }

        $canonicalUsername = $user ? $user->username : $empId;
        $credentials = ['username' => $canonicalUsername, 'password' => $password];

        // Also fallback to trimmed password if user accidentally added leading/trailing whitespace
        $authenticated = Auth::attempt($credentials) || (trim($password) !== $password && Auth::attempt(['username' => $canonicalUsername, 'password' => trim($password)]));

        if ($authenticated) {
            $authUser = Auth::user();
            if ($authUser) {
                $authUser->failed_login_attempts = 0;
                $authUser->requires_password_reset = false;
                $authUser->save();

                // Prime session with user identity to eliminate redundant SQL queries across all routes
                $profile = $authUser->profile;
                $roleName = $profile->roles_id ?? 'stu';
                $fullName = $roleName === 'sa' ? 'Super Admin' : trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
                $firstName = $profile->first_name ? ucwords(strtolower($profile->first_name)) : $authUser->username;

                session([
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
                ['user' => $user]
            );

            return redirect()->route('dashboard');
        }

        if ($user) {
            $user->failed_login_attempts += 1;
            
            if ($user->failed_login_attempts >= 3) {
                $user->requires_password_reset = true;
                $user->save();

                \App\Services\ActivityLogger::log(
                    'account_locked',
                    'Account Locked (3 Failed Logins)',
                    'Authentication',
                    'User account ' . $user->username . ' locked after 3 consecutive failed login attempts.',
                    'danger',
                    ['user' => $user, 'department_id' => $user->profile?->departments_id]
                );

                return back()->withErrors([
                    'emp_id' => 'Your account has been locked due to 3 failed attempts. Please contact an administrator to reset your password.',
                ]);
            }
            
            $user->save();

            \App\Services\ActivityLogger::log(
                'failed_login',
                'Failed Login Attempt',
                'Authentication',
                'Failed password attempt for username ' . $user->username . '.',
                'warning',
                ['user' => $user, 'department_id' => $user->profile?->departments_id]
            );
            
            $attemptsLeft = 3 - $user->failed_login_attempts;
            return back()->withErrors([
                'emp_id' => "Invalid Username / Employee ID or Password. You have {$attemptsLeft} attempt(s) left before your account is locked.",
            ]);
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
}
