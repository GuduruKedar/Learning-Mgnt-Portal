<?php

namespace Tests\Feature;

use App\Mail\ServerErrorReported;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ErrorReportTest extends TestCase
{
    /**
     * Test that the 500 error page renders correctly with the Report Error button.
     */
    public function test_500_page_renders_with_report_button(): void
    {
        $response = $this->get('/500-preview');

        $response->assertStatus(500);
        $response->assertSee('500');
        $response->assertSee('Internal Server Error');
        $response->assertSee('Report Error');
        $response->assertSee('web@vignan.ac.in');
        $response->assertSee('report-error');
    }

    /**
     * Test that submitting an error report logs to ActivityLog and triggers an email to admin.
     */
    public function test_report_error_endpoint_logs_activity_and_sends_email(): void
    {
        Mail::fake();

        $response = $this->postJson('/report-error', [
            'status_code'   => 500,
            'error_message' => 'Call to undefined method App\Services\Foo::bar()',
            'url'           => 'http://127.0.0.1:8000/dashboard',
            'notes'         => 'Clicked on submit button while uploading assignment',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        // Check Activity Log was written to the database
        $this->assertDatabaseHas('activity_logs', [
            'action'      => 'server_500_error_reported',
            'action_title'=> '500 Server Error Reported',
            'module'      => 'System',
            'severity'    => 'danger',
        ]);

        $log = ActivityLog::where('action', 'server_500_error_reported')->latest()->first();
        $this->assertNotNull($log);
        $this->assertEquals(500, $log->payload['status_code']);
        $this->assertEquals('Call to undefined method App\Services\Foo::bar()', $log->payload['error_message']);
        $this->assertEquals('http://127.0.0.1:8000/dashboard', $log->payload['reported_url']);
        $this->assertEquals('Clicked on submit button while uploading assignment', $log->payload['user_note']);

        // Check Mail was sent to admin
        Mail::assertSent(ServerErrorReported::class, function ($mail) {
            return $mail->hasTo('web@vignan.ac.in') &&
                   $mail->reportData['status_code'] === 500;
        });
    }

    /**
     * Test that an authenticated user reporting an error associates their details in the log.
     */
    public function test_authenticated_user_error_report(): void
    {
        Mail::fake();

        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->postJson('/report-error', [
            'status_code'   => 500,
            'error_message' => 'SQLSTATE[HY000]: General error',
            'url'           => 'http://127.0.0.1:8000/students',
            'notes'         => 'Filtering students by regulation',
        ]);

        $response->assertStatus(200);

        $log = ActivityLog::where('action', 'server_500_error_reported')->latest()->first();
        $this->assertNotNull($log);
        $this->assertEquals($user->id, $log->user_id);

        Mail::assertSent(ServerErrorReported::class);
    }

    /**
     * Test that incoming webhook triggers email alert and logs to activity logs.
     */
    public function test_webhook_triggers_email_and_activity_log(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/webhook/error', [
            'status_code'   => 500,
            'error_message' => 'Database connection timeout detected by monitoring agent',
            'url'           => 'http://127.0.0.1:8000/courses',
            'source'        => 'HealthCheck Service',
            'notes'         => 'Replica lag exceeded threshold',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        // Check Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'action'   => 'server_500_error_reported',
            'module'   => 'System',
            'severity' => 'danger',
        ]);

        // Check Mail was dispatched to web@vignan.ac.in
        Mail::assertSent(ServerErrorReported::class, function ($mail) {
            return $mail->hasTo('web@vignan.ac.in') &&
                   str_contains($mail->reportData['error_message'], 'Database connection timeout');
        });
    }

    /**
     * Test that ErrorMailAlertService handles exceptions and sends email alert.
     */
    public function test_error_mail_alert_service_dispatches_email_on_exception(): void
    {
        Mail::fake();

        $exception = new \RuntimeException('Critical unexpected crash during calculation');
        $handled = \App\Services\ErrorMailAlertService::sendException($exception);

        $this->assertTrue($handled);

        Mail::assertSent(ServerErrorReported::class, function ($mail) {
            return $mail->hasTo('web@vignan.ac.in') &&
                   $mail->reportData['error_message'] === 'Critical unexpected crash during calculation';
        });

        $this->assertDatabaseHas('activity_logs', [
            'action'   => 'server_500_exception_captured',
            'module'   => 'System',
            'severity' => 'danger',
        ]);
    }

    /**
     * Test that artisan error:test-mail command dispatches test email alert.
     */
    public function test_artisan_error_test_mail_command(): void
    {
        Mail::fake();

        $this->artisan('error:test-mail')
             ->assertSuccessful();

        Mail::assertSent(ServerErrorReported::class, function ($mail) {
            return $mail->hasTo('web@vignan.ac.in');
        });
    }
}
