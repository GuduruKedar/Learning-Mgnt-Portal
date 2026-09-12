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

        $user = \App\Models\User::where('username', $request->emp_id)->first();

        if ($user && $user->requires_password_reset) {
            return back()->withErrors([
                'emp_id' => 'Your account has been locked due to too many failed attempts. Please contact an administrator to reset your password.',
            ]);
        }

        if (Auth::attempt(['username' => $request->emp_id, 'password' => $request->password])) {
            if ($user) {
                $user->failed_login_attempts = 0;
                $user->save();
            }
            
            $request->session()->regenerate();

            /*
            // FUNCTIONAL BLOCK: 30-minute session per user
            // Keep this functionality but do not implement it actively yet.
            // You can use this to expire the user's session after 30 minutes.
            $request->session()->put('session_expires_at', now()->addMinutes(30));
            // You would then check this in a Middleware to log the user out if time is up.
            */

            return redirect()->route('dashboard');
        }

        if ($user) {
            $user->failed_login_attempts += 1;
            
            if ($user->failed_login_attempts >= 3) {
                $user->requires_password_reset = true;
                $user->save();
                return back()->withErrors([
                    'emp_id' => 'Your account has been locked due to 3 failed attempts. Please contact an administrator to reset your password.',
                ]);
            }
            
            $user->save();
            
            $attemptsLeft = 3 - $user->failed_login_attempts;
            return back()->withErrors([
                'emp_id' => "Invalid Employee ID or Password. You have {$attemptsLeft} attempt(s) left before your account is locked.",
            ]);
        }

        return back()->withErrors([
            'emp_id' => 'Invalid Employee ID or Password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
