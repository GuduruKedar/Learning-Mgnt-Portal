<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServerErrorReported extends Mailable
{
    use Queueable, SerializesModels;

    public array $reportData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('[LMS Alert] ' . ($this->reportData['status_code'] ?? 500) . ' Server Error Reported')
                    ->view('emails.server_error_reported')
                    ->with('data', $this->reportData);
    }
}
