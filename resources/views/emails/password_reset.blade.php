<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Password Reset - Temporary Credentials</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; line-height: 1.6;">

    <!-- Outer Wrapper -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 32px 16px;">
        <tr>
            <td align="center">
                <!-- Main Container Card -->
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%); padding: 36px 32px; text-align: left;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <div style="display: inline-block; background-color: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.35); padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; color: #fde68a; text-transform: uppercase; letter-spacing: 0.75px; margin-bottom: 12px;">
                                            🔐 Security Notice • Password Reset
                                        </div>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                            Password Reset Notification
                                        </h1>
                                        <p style="margin: 8px 0 0 0; color: #c7d2fe; font-size: 14px;">
                                            A new temporary password has been generated for your LMS account.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Body Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <!-- Greeting -->
                            <p style="margin: 0 0 16px 0; font-size: 16px; font-weight: 600; color: #0f172a;">
                                Hello {{ $displayName }},
                            </p>
                            <p style="margin: 0 0 24px 0; font-size: 14px; color: #475569; line-height: 1.6;">
                                We received a request to reset the password for your <strong>Learning Management System (LMS)</strong> account. Your password has been successfully reset. Please use the temporary credentials below to sign in:
                            </p>

                            <!-- Credentials Box -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px; overflow: hidden;">
                                <tr>
                                    <td colspan="2" style="background-color: #f1f5f9; padding: 12px 20px; border-bottom: 1px solid #e2e8f0;">
                                        <span style="font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">
                                            🔑 Your Updated Credentials
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; width: 35%; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                        Portal URL
                                    </td>
                                    <td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 500; color: #4338ca;">
                                        <a href="{{ $loginUrl }}" style="color: #4338ca; text-decoration: underline; word-break: break-all;">{{ $loginUrl }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                        Username / ID
                                    </td>
                                    <td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 15px; font-family: 'Courier New', Courier, monospace; font-weight: 700; color: #0f172a;">
                                        {{ $user->username }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                        Temporary Password
                                    </td>
                                    <td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9;">
                                        <code style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 6px 12px; border-radius: 6px; font-family: 'Courier New', Courier, monospace; font-size: 15px; font-weight: 800; display: inline-block; letter-spacing: 0.5px;">
                                            {{ $temporaryPassword }}
                                        </code>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                        Reset Timestamp
                                    </td>
                                    <td style="padding: 14px 20px; font-size: 13px; font-weight: 500; color: #334155;">
                                        {{ $resetTime }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Security Notice: Immediate Password Change Required -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 8px; margin-bottom: 28px; border-top: 1px solid #fef3c7; border-right: 1px solid #fef3c7; border-bottom: 1px solid #fef3c7;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <div style="font-size: 14px; font-weight: 700; color: #92400e; margin-bottom: 6px;">
                                            ⚠️ Immediate Action Required: Change Your Password
                                        </div>
                                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #78350f; line-height: 1.5;">
                                            This temporary password was generated to allow you to recover account access. For your personal security and privacy, you must <strong>log in and update your password immediately</strong>.
                                        </p>
                                        <div style="font-size: 12px; color: #92400e; line-height: 1.6;">
                                            <strong>Steps to secure your account:</strong>
                                            <ol style="margin: 6px 0 0 0; padding-left: 18px;">
                                                <li style="margin-bottom: 4px;">Click the <strong>"Login to LMS Portal"</strong> button below.</li>
                                                <li style="margin-bottom: 4px;">Sign in with your Username (<strong>{{ $user->username }}</strong>) and this temporary password.</li>
                                                <li style="margin-bottom: 4px;">Go to your profile settings and choose <strong>"Change Password"</strong> to set a permanent, secure password.</li>
                                            </ol>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Unauthorized Notice Alert -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px; margin-bottom: 28px; border-top: 1px solid #fee2e2; border-right: 1px solid #fee2e2; border-bottom: 1px solid #fee2e2;">
                                <tr>
                                    <td style="padding: 14px 18px;">
                                        <div style="font-size: 12px; font-weight: 700; color: #991b1b; margin-bottom: 4px;">
                                            Did not request this password reset?
                                        </div>
                                        <p style="margin: 0; font-size: 12px; color: #b91c1c; line-height: 1.5;">
                                            If you did not initiate this request, someone may be trying to access your account. Please log in immediately using the credentials above, change your password, and inform your Department Coordinator or University IT Administrator.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 36px; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.25), 0 2px 4px -2px rgba(79, 70, 229, 0.25); text-align: center;">
                                            Login to LMS Portal &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Direct Link Fallback -->
                            <p style="margin: 0 0 20px 0; font-size: 12px; color: #64748b; text-align: center; line-height: 1.5;">
                                If the button above does not work, copy and paste this link into your browser:<br>
                                <a href="{{ $loginUrl }}" style="color: #4f46e5; word-break: break-all;">{{ $loginUrl }}</a>
                            </p>

                            <!-- Security Reminder Note -->
                            <div style="padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                                <strong style="color: #64748b;">Security Tip:</strong> Never share your password with anyone. University administrators will never ask for your password.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 32px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 600; color: #475569;">
                                Learning Management System (LMS) • VFSTR Vignan University
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94a3b8;">
                                This is an automated security notification sent to {{ $profile?->email ?? 'your registered email' }}. Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
