<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error Alert</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border: 1px solid #e5e7eb;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc2626; padding: 24px 32px; text-align: left;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <div style="display: inline-block; background-color: rgba(255, 255, 255, 0.2); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                                            High Priority System Alert
                                        </div>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 700;">
                                            HTTP {{ $data['status_code'] ?? 500 }} Server Error Reported
                                        </h1>
                                        <p style="margin: 6px 0 0 0; color: #fecaca; font-size: 13px;">
                                            A user encountered an unexpected server exception and submitted an incident report.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.5; color: #374151;">
                                Hello Administrator,
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #4b5563;">
                                A server error was encountered and explicitly reported on the LMS application. The incident has been recorded in the central Superadmin Activity Logs.
                            </p>

                            <!-- Incident Details Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Target URL</span>
                                        <div style="font-size: 13px; font-family: monospace; color: #111827; word-break: break-all; margin-top: 4px;">
                                            {{ $data['url'] ?? 'N/A' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Error Message</span>
                                        <div style="font-size: 13px; color: #b91c1c; font-weight: 600; margin-top: 4px; word-break: break-word;">
                                            {{ $data['error_message'] ?? 'Internal Server Error' }}
                                        </div>
                                    </td>
                                </tr>
                                @if(!empty($data['user_note']))
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; background-color: #fffbeb;">
                                        <span style="font-size: 11px; font-weight: 600; color: #b45309; text-transform: uppercase;">User Note / What they were doing</span>
                                        <div style="font-size: 13px; color: #92400e; margin-top: 4px; font-style: italic;">
                                            "{{ $data['user_note'] }}"
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="50%" style="vertical-align: top;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Reported By</span>
                                                    <div style="font-size: 13px; font-weight: 600; color: #111827; margin-top: 4px;">
                                                        {{ $data['user_name'] ?? 'Guest' }} ({{ strtoupper($data['user_role'] ?? 'guest') }})
                                                    </div>
                                                    <div style="font-size: 12px; color: #6b7280;">{{ $data['user_email'] ?? 'N/A' }}</div>
                                                </td>
                                                <td width="50%" style="vertical-align: top;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Reported At</span>
                                                    <div style="font-size: 13px; color: #111827; margin-top: 4px;">
                                                        {{ $data['reported_at'] ?? now()->toDayDateTimeString() }}
                                                    </div>
                                                    <div style="font-size: 11px; color: #9ca3af;">IP: {{ $data['ip'] ?? '127.0.0.1' }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Call to Action Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top: 10px; margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $data['activity_logs_url'] ?? url('/activity-logs') }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);">
                                            View in Superadmin Activity Logs &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 12px; color: #6b7280; line-height: 1.5; text-align: center;">
                                You received this notification because you are registered as an administrator for the Learning Management System.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 18px 32px; border-top: 1px solid #e5e7eb; text-align: center;">
                            <span style="font-size: 12px; color: #9ca3af;">
                                LMS Error Monitoring Service &bull; Contact: <a href="mailto:web@vignan.ac.in" style="color: #6366f1; text-decoration: none;">web@vignan.ac.in</a>
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
