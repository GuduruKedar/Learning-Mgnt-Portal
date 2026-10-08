<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class DiscordWebhookService
{
    /**
     * Send an unhandled exception or critical error to Discord webhook.
     *
     * @param Throwable $e
     * @param Request|null $request
     * @return bool
     */
    public static function sendException(Throwable $e, ?Request $request = null): bool
    {
        try {
            $config = config('discord_webhook');

            if (empty($config['enabled']) || empty($config['webhook_url'])) {
                return false;
            }

            // Check if exception type is in the ignored list
            $ignored = $config['ignored_exceptions'] ?? [];
            foreach ($ignored as $ignoredClass) {
                if ($e instanceof $ignoredClass) {
                    return false;
                }
            }

            $request = $request ?: (app()->bound('request') ? request() : null);

            // Deduplication via cache
            $rateLimit = (int) ($config['rate_limit_seconds'] ?? 30);
            if ($rateLimit > 0) {
                $hash = md5($e->getFile() . ':' . $e->getLine() . ':' . $e->getMessage() . ':' . ($request ? $request->path() : 'cli'));
                $cacheKey = 'discord_err_wh_' . $hash;

                if (Cache::has($cacheKey)) {
                    return false;
                }

                Cache::put($cacheKey, true, now()->addSeconds($rateLimit));
            }

            $embed = self::buildExceptionEmbed($e, $request, $config);

            $payload = [
                'username' => $config['bot_name'] ?? 'LMS Error Monitor',
                'avatar_url' => $config['avatar_url'] ?? null,
                'content' => !empty($config['mention']) ? $config['mention'] : null,
                'embeds' => [$embed],
            ];

            return self::dispatchWebhook($config['webhook_url'], $payload);
        } catch (Throwable $internalError) {
            // Never let webhook failure break application error reporting
            Log::channel('daily')->warning('Discord Webhook Exception Delivery Failed: ' . $internalError->getMessage());
            return false;
        }
    }

    /**
     * Send a custom formatted event or notification card to Discord.
     *
     * @param string $title
     * @param string $description
     * @param int $color Hex color integer (e.g. 0x4F46E5 for Indigo, 0x10B981 for Emerald)
     * @param array $fields Array of ['name' => ..., 'value' => ..., 'inline' => bool]
     * @return bool
     */
    public static function sendMessage(string $title, string $description, int $color = 0x4F46E5, array $fields = []): bool
    {
        try {
            $config = config('discord_webhook');

            if (empty($config['enabled']) || empty($config['webhook_url'])) {
                return false;
            }

            $embed = [
                'title' => substr($title, 0, 256),
                'description' => substr($description, 0, 2048),
                'color' => $color,
                'fields' => array_slice($fields, 0, 25),
                'footer' => [
                    'text' => config('app.name', 'LMS Portal') . ' • Environment: ' . app()->environment(),
                ],
                'timestamp' => now()->toIso8601String(),
            ];

            $payload = [
                'username' => $config['bot_name'] ?? 'LMS Alert Bot',
                'avatar_url' => $config['avatar_url'] ?? null,
                'embeds' => [$embed],
            ];

            return self::dispatchWebhook($config['webhook_url'], $payload);
        } catch (Throwable $e) {
            Log::channel('daily')->warning('Discord Webhook Message Delivery Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a test webhook ping to verify Discord connection.
     *
     * @return array ['success' => bool, 'message' => string]
     */
    public static function sendTestPing(): array
    {
        $config = config('discord_webhook');

        if (empty($config['webhook_url'])) {
            return [
                'success' => false,
                'message' => 'DISCORD_ERROR_WEBHOOK_URL is not configured in your .env file.',
            ];
        }

        $fields = [
            [
                'name' => '🌐 Application',
                'value' => config('app.name', 'LMS') . ' (' . app()->environment() . ')',
                'inline' => true,
            ],
            [
                'name' => '⚡ PHP Version',
                'value' => PHP_VERSION,
                'inline' => true,
            ],
            [
                'name' => '📦 Laravel Version',
                'value' => app()->version(),
                'inline' => true,
            ],
            [
                'name' => '🕒 Server Time',
                'value' => now()->toDateTimeString() . ' (' . config('app.timezone') . ')',
                'inline' => false,
            ],
        ];

        $sent = self::sendMessage(
            '🔔 Discord Webhook Trigger Connected Successfully!',
            'This is a verification test message from **' . config('app.name', 'LMS Portal') . '** in the `' . app()->environment() . '` environment.',
            0x10B981, // Emerald Green
            $fields
        );

        return [
            'success' => $sent,
            'message' => $sent ? 'Test webhook delivered successfully to Discord!' : 'Failed to deliver webhook. Please verify your Discord webhook URL and internet connection.',
        ];
    }

    /**
     * Build the structured Discord embed payload for an Exception.
     */
    protected static function buildExceptionEmbed(Throwable $e, ?Request $request, array $config): array
    {
        $appEnv = strtoupper(app()->environment());
        $appName = config('app.name', 'LMS Portal');

        $fields = [
            [
                'name' => '🚨 Exception Class',
                'value' => '`' . get_class($e) . '`',
                'inline' => false,
            ],
            [
                'name' => '📍 File & Line',
                'value' => '`' . self::cleanFilePath($e->getFile()) . ':' . $e->getLine() . '`',
                'inline' => false,
            ],
        ];

        // HTTP Context if request is active
        if ($request) {
            $userStr = 'Guest (Unauthenticated)';
            if ($user = $request->user()) {
                $userStr = "#{$user->id} - {$user->username} ({$user->role})";
            }

            $fields[] = [
                'name' => '🔗 Request Route',
                'value' => '`[' . $request->method() . ']` ' . $request->fullUrl(),
                'inline' => false,
            ];

            $fields[] = [
                'name' => '👤 User Context',
                'value' => $userStr,
                'inline' => true,
            ];

            $fields[] = [
                'name' => '🖥️ Client IP',
                'value' => $request->ip() ?? 'Unknown',
                'inline' => true,
            ];

            // Attach sanitized request payload if enabled
            if (!empty($config['include_request_body']) && !empty($request->all())) {
                $sanitized = self::sanitizeRequestData($request->all(), $config['hidden_fields'] ?? []);
                $jsonPayload = json_encode($sanitized, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (strlen($jsonPayload) > 900) {
                    $jsonPayload = substr($jsonPayload, 0, 900) . '...';
                }

                $fields[] = [
                    'name' => '📦 Request Payload',
                    'value' => "```json\n" . $jsonPayload . "\n```",
                    'inline' => false,
                ];
            }
        } else {
            $fields[] = [
                'name' => '⚙️ Execution Context',
                'value' => 'CLI / Artisan Console / Background Queue',
                'inline' => true,
            ];
        }

        // Trace Snippet
        $traceSnippet = self::formatTraceSnippet($e);
        if (!empty($traceSnippet)) {
            $fields[] = [
                'name' => '📜 Stack Trace Snippet',
                'value' => "```\n" . $traceSnippet . "\n```",
                'inline' => false,
            ];
        }

        $message = $e->getMessage() ?: '(No exception message provided)';
        if (strlen($message) > 1800) {
            $message = substr($message, 0, 1800) . '...';
        }

        return [
            'title' => "⚠️ [{$appEnv}] Error Triggered in {$appName}",
            'description' => "**" . $message . "**",
            'color' => 0xDC2626, // Red
            'fields' => $fields,
            'footer' => [
                'text' => "{$appName} • Environment: {$appEnv} • PHP " . PHP_VERSION,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Dispatch HTTP POST request to the Discord Webhook URL.
     */
    protected static function dispatchWebhook(string $url, array $payload): bool
    {
        $response = Http::timeout(3)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        return $response->successful();
    }

    /**
     * Sanitize sensitive form parameters (passwords, tokens, etc.).
     */
    protected static function sanitizeRequestData(array $data, array $hiddenKeys): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (in_array(strtolower((string) $key), array_map('strtolower', $hiddenKeys), true)) {
                $result[$key] = '******** [REDACTED]';
            } elseif (is_array($value)) {
                $result[$key] = self::sanitizeRequestData($value, $hiddenKeys);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Format the top stack trace frames into a readable summary snippet.
     */
    protected static function formatTraceSnippet(Throwable $e): string
    {
        $frames = array_slice($e->getTrace(), 0, 6);
        $lines = [];

        foreach ($frames as $i => $frame) {
            $file = isset($frame['file']) ? self::cleanFilePath($frame['file']) : '[internal function]';
            $line = $frame['line'] ?? '';
            $call = ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? '') . '()';
            $lines[] = "#{$i} {$file}" . ($line ? ":{$line}" : '') . " -> {$call}";
        }

        $snippet = implode("\n", $lines);
        if (strlen($snippet) > 950) {
            $snippet = substr($snippet, 0, 950) . '...';
        }

        return $snippet;
    }

    /**
     * Clean absolute server paths for concise presentation.
     */
    protected static function cleanFilePath(string $path): string
    {
        $base = base_path();
        if (str_starts_with($path, $base)) {
            return ltrim(substr($path, strlen($base)), '/\\');
        }
        return basename($path);
    }
}
