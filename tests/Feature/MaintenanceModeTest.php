<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    protected function tearDown(): void
    {
        // Always ensure the application is brought back online after tests
        Artisan::call('up');
        parent::tearDown();
    }

    /**
     * Test that the maintenance preview route loads the custom 503 template.
     */
    public function test_maintenance_preview_route_renders_custom_503_template()
    {
        $response = $this->get('/maintenance-preview');

        $response->assertStatus(503);
        $response->assertSee('System Under Maintenance');
        $response->assertSee('Learning Management');
        $response->assertSee('Check Server Status');
        $response->assertSee('php artisan up');
        $response->assertSee('Scheduled Routine Maintenance');
        $response->assertSee('support@vignan.ac.in');
    }

    /**
     * Test that entering maintenance mode via artisan down serves the 503 template.
     */
    public function test_artisan_down_puts_application_in_maintenance_mode_and_renders_template()
    {
        // 1. Enter maintenance mode
        Artisan::call('down');

        // 2. Visiting the application should return 503
        $response = $this->get('/');
        $response->assertStatus(503);
        $response->assertSee('System Under Maintenance');
        $response->assertSee('Scheduled Routine Maintenance');

        // 3. Bring application back up
        Artisan::call('up');

        // 4. Visiting application after artisan up returns 200
        $responseLive = $this->get('/');
        $responseLive->assertStatus(200);
    }
}
