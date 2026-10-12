<?php

namespace App\Http\Controllers;

use App\Mail\ServerErrorReported;
use App\Models\Profile;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorReportController extends Controller
{
    /**
     * Handle incoming error report from 500 page.
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'error_message' => 'nullable|string|max:1000',
            'url'           => 'nullable|string|max:2000',
            'notes'         => 'nullable|string|max:2000',
            'status_code'   => 'nullable|integer',
        ]);

        $errorMessage = $validated['error_message'] ?? '500 Internal Server Error';
        $errorUrl = $validated['url'] ?? $request->header('referer') ?? url()->previous() ?? url('/');
        $userNotes = $validated['notes'] ?? null;
        $statusCode = $validated['status_code'] ?? 500;

        /** @var User|null $user */
        $user = Auth::user();
        $userName = $user ? $user->full_name : 'Guest User';
        $userEmail = $user?->profile?->email ?? ($user?->username ?? 'Guest / Unauthenticated');
        $userRole = $user ? $user->role : 'guest';
        $ip = $request->ip() ?? '127.0.0.1';
        $userAgent = $request->userAgent() ?? 'Unknown';

        // 1. Log to Activity Logger (Superadmin panel: /activity-logs)
        $description = "User reported a {$statusCode} Server Error on URL: {$errorUrl}";
        if ($userNotes) {
            $description .= " | User Note: {$userNotes}";
        }

        $payload = [
            'status_code'   => $statusCode,
            'error_message' => $errorMessage,
            'reported_url'  => $errorUrl,
            'user_note'     => $userNotes,
            'reported_by'   => $userName,
            'user_email'    => $userEmail,
            'user_role'     => $userRole,
            'ip_address'    => $ip,
            'user_agent'    => $userAgent,
            'reported_at'   => now()->format('Y-m-d H:i:s'),
        ];

        $activityLog = ActivityLogger::log(
            action: 'server_500_error_reported',
            actionTitle: "{$statusCode} Server Error Reported",
            module: 'System',
            description: $description,
            severity: 'danger',
            options: [
                'user'        => $user,
                'entity_type' => 'ServerError',
                'entity_name' => "HTTP {$statusCode} Error",
                'url'         => $errorUrl,
                'payload'     => $payload,
            ]
        );

        // 2. Admin notification recipient email (web@vignan.ac.in)
        $recipients = config('mail.admin_alert_email', 'web@vignan.ac.in');

        // 3. Dispatch notification email to admin
        $emailSent = false;
        try {
            Mail::to($recipients)->send(new ServerErrorReported([
                'status_code'       => $statusCode,
                'error_message'     => $errorMessage,
                'url'               => $errorUrl,
                'user_note'         => $userNotes,
                'user_name'         => $userName,
                'user_email'        => $userEmail,
                'user_role'         => $userRole,
                'ip'                => $ip,
                'user_agent'        => $userAgent,
                'reported_at'       => now()->format('M d, Y h:i A'),
                'activity_log_id'   => $activityLog?->id,
                'activity_logs_url' => url('/activity-logs'),
            ]));
            $emailSent = true;
        } catch (\Throwable $mailException) {
            Log::warning('ErrorReportController: Mail delivery failed: ' . $mailException->getMessage());
        }

        return response()->json([
            'status'     => 'success',
            'message'    => 'Thank you! The error report has been sent to the administrator (web@vignan.ac.in) and logged in the system.',
            'email_sent' => $emailSent,
            'log_id'     => $activityLog?->id,
        ]);
    }

    /**
     * Handle incoming webhook triggers that fire an email alert to administrators.
     */
    public function handleWebhook(Request $request)
    {
        $validated = $request->validate([
            'error_message' => 'nullable|string|max:1000',
            'url'           => 'nullable|string|max:2000',
            'notes'         => 'nullable|string|max:2000',
            'status_code'   => 'nullable|integer',
            'source'        => 'nullable|string|max:100',
        ]);

        $source = $validated['source'] ?? 'External Webhook Monitor';
        $errorMessage = $validated['error_message'] ?? 'Error Alert received via Webhook';
        $errorUrl = $validated['url'] ?? $request->header('referer') ?? url('/');
        $notes = !empty($validated['notes']) ? "Webhook Source: {$source} | " . $validated['notes'] : "Webhook Source: {$source}";
        $statusCode = $validated['status_code'] ?? 500;

        $request->merge([
            'error_message' => $errorMessage,
            'url'           => $errorUrl,
            'notes'         => $notes,
            'status_code'   => $statusCode,
        ]);

        return $this->report($request);
    }
}
