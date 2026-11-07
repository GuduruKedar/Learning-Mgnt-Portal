<?php

namespace App\Http\Middleware;

use App\Services\UserSessionTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserSessionDuration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track session duration for authenticated users on web requests
        if ($request->hasSession()) {
            UserSessionTracker::trackRequest($request);
        }

        return $response;
    }
}

