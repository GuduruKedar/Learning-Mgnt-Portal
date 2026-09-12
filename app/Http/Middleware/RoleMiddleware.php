<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        \Log::info('RoleMiddleware check:', ['userRole' => $userRole, 'roles' => $roles, 'url' => $request->url()]);

        if (!in_array($userRole, $roles)) {
            \Log::warning('RoleMiddleware rejected unauthorized access:', ['userRole' => $userRole, 'roles' => $roles, 'url' => $request->url()]);
            return redirect()->back()->with('error', 'Unauthorized action. Your role does not have permission to access this page.');
        }

        return $next($request);
    }
}
