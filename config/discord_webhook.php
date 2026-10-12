<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Discord Error Webhook Enabled
    |--------------------------------------------------------------------------
    |
    | When enabled, unhandled exceptions and errors will be sent to the configured
    | Discord webhook channel in real-time.
    |
    */
    'enabled' => env('DISCORD_ERROR_WEBHOOK_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Discord Webhook URL
    |--------------------------------------------------------------------------
    |
    | The Discord incoming webhook URL obtained from Server Settings -> Integrations.
    |
    */
    'webhook_url' => env('DISCORD_ERROR_WEBHOOK_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Delivery Mode
    |--------------------------------------------------------------------------
    |
    | 'sync'  : Sends webhook immediately within the request cycle.
    | 'queue' : Pushes webhook payload to the Laravel queue for background execution.
    |
    */
    'mode' => env('DISCORD_ERROR_WEBHOOK_MODE', 'sync'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connection and Queue Name
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'connection' => env('DISCORD_ERROR_WEBHOOK_QUEUE_CONNECTION', null),
        'name' => env('DISCORD_ERROR_WEBHOOK_QUEUE_NAME', 'default'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting / Deduplication (in seconds)
    |--------------------------------------------------------------------------
    |
    | Prevents flooding Discord with duplicate error messages if the same
    | error is triggered repeatedly within a short timeframe.
    | Set to 0 to disable deduplication.
    |
    */
    'rate_limit_seconds' => (int) env('DISCORD_ERROR_WEBHOOK_RATE_LIMIT', 30),

    /*
    |--------------------------------------------------------------------------
    | Bot Customization
    |--------------------------------------------------------------------------
    */
    'bot_name' => env('DISCORD_ERROR_WEBHOOK_BOT_NAME', 'LMS Error Monitor'),
    'avatar_url' => env('DISCORD_ERROR_WEBHOOK_AVATAR_URL', 'https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logomark-cmyk.svg'),

    /*
    |--------------------------------------------------------------------------
    | Optional Role / User Mention on Critical Errors
    |--------------------------------------------------------------------------
    |
    | Set a Discord Role ID (e.g., "<@&123456789>") or User ID ("<@123456789>")
    | to ping in the notification.
    |
    */
    'mention' => env('DISCORD_ERROR_WEBHOOK_MENTION', null),

    /*
    |--------------------------------------------------------------------------
    | Include Request Body Payload
    |--------------------------------------------------------------------------
    |
    | Whether to attach the sanitized HTTP request payload with the error.
    |
    */
    'include_request_body' => env('DISCORD_WEBHOOK_INCLUDE_BODY', true),

    /*
    |--------------------------------------------------------------------------
    | Sensitive Fields to Redact from Request Body
    |--------------------------------------------------------------------------
    */
    'hidden_fields' => [
        'password',
        'password_confirmation',
        'current_password',
        'token',
        '_token',
        'secret',
        'authorization',
        'card_number',
        'cvv',
        'api_key',
        'key',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignored Exceptions
    |--------------------------------------------------------------------------
    |
    | List of exception classes that should NEVER be reported to Discord
    | (such as normal validation errors or 404 not found).
    |
    */
    'ignored_exceptions' => [
        \Illuminate\Validation\ValidationException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Illuminate\Session\TokenMismatchException::class,
    ],
];
