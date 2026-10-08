<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            \App\Services\DiscordWebhookService::sendException($e);
        });

        $this->renderable(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            $message = 'The uploaded file exceeds the 25MB maximum size limit. Please select a file smaller than 25MB.';

            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => [
                        'file' => [$message]
                    ]
                ], 422);
            }

            if ($request->hasSession()) {
                $request->session()->flash('error', $message);
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'file' => $message
                    ]);
            }

            return redirect()->back()->with('error', $message);
        });
    }
}
