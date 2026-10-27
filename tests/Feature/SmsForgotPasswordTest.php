<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Profile;
use App\Models\School;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'smsapi.jiocx.com/*' => Http::response([
                'status' => 'success',
                'message' => 'Message submitted successfully',
                'msgid' => '1234567890'
            ], 200),
        ]);
    }

    public function test_sms_service_sends_credentials_with_correct_payload()
    {
        $sms = new SmsService();
        $result = $sms->sendCredentials('9440151948', 'TEST_USER_01', 'Secret@123');

        $this->assertTrue($result['success']);

        Http::assertSent(function ($request) {
            $data = $request->data();
            return $request->url() === 'https://smsapi.jiocx.com/apggw/vignans/sms/v1/send'
                && $data['username'] === 'jiocx-vignans'
                && $data['to'] === '9440151948'
                && $data['sender_id'] === 'VIGNAN'
                && $data['dlt_entity_id'] === '1101385640000011255'
                && $data['dlt_template_id'] === '1777179265526294215'
                && str_contains($data['body'], 'Username:TEST_USER_01')
                && str_contains($data['body'], 'Password: Secret@123')
                && str_contains($data['body'], '-- Vignan University.');
        });
    }

    public function test_forgot_password_fails_if_user_does_not_exist()
    {
        $response = $this->from('/')
            ->post(route('password.forgot'), [
                'username' => 'NON_EXISTENT_USER_99999',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('forgot_error');
        $this->assertStringContainsString('No user account was found', session('forgot_error'));
    }

    public function test_forgot_password_fails_if_user_has_no_phone_number()
    {
        $profile = Profile::create([
            'username' => 'NOPHONE_STU_01',
            'first_name' => 'NoPhone',
            'last_name' => 'Student',
            'phone' => null,
            'roles_id' => 'stu',
        ]);

        $user = User::create([
            'username' => 'NOPHONE_STU_01',
            'password' => Hash::make('OldPassword123'),
            'profile_id' => $profile->id,
        ]);

        $response = $this->from('/')
            ->post(route('password.forgot'), [
                'username' => 'NOPHONE_STU_01',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('forgot_error');
        $this->assertStringContainsString('No mobile phone number is linked', session('forgot_error'));
    }

    public function test_forgot_password_succeeds_resets_password_and_dispatches_sms()
    {
        $profile = Profile::create([
            'username' => 'STU_FORGOT_01',
            'first_name' => 'ValidPhone',
            'last_name' => 'Student',
            'phone' => '9876543210',
            'roles_id' => 'stu',
        ]);

        $user = User::create([
            'username' => 'STU_FORGOT_01',
            'password' => Hash::make('OldPassword123'),
            'profile_id' => $profile->id,
            'failed_login_attempts' => 2,
            'requires_password_reset' => false,
        ]);

        $response = $this->from('/')
            ->post(route('password.forgot'), [
                'username' => 'STU_FORGOT_01',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('forgot_success');
        $this->assertStringContainsString('98******10', session('forgot_success'));

        $user->refresh();
        $this->assertEquals(0, $user->failed_login_attempts);
        $this->assertFalse((bool) $user->requires_password_reset);

        // SMS was dispatched with new password
        Http::assertSent(function ($request) {
            return $request['to'] === '9876543210'
                && str_contains($request['body'], 'Username:STU_FORGOT_01');
        });
    }

    public function test_coordinator_can_reset_password_by_username()
    {
        $dept = Department::first();
        if (!$dept) {
            $school = School::first() ?? School::create(['name' => 'Engineering', 'code' => 'sc_eng']);
            $dept = Department::create(['name' => 'Computer Science', 'code' => 'dep_cse', 'school_id' => $school->code]);
        }

        $coordProfile = Profile::create([
            'username' => 'COORD_TEST_01',
            'first_name' => 'Dept',
            'last_name' => 'Coordinator',
            'phone' => '9999988888',
            'roles_id' => 'admin',
            'departments_id' => $dept->code,
        ]);

        $coordinator = User::create([
            'username' => 'COORD_TEST_01',
            'password' => Hash::make('CoordPass123'),
            'profile_id' => $coordProfile->id,
        ]);

        $stuProfile = Profile::create([
            'username' => 'STU_DEPT_01',
            'first_name' => 'Dept',
            'last_name' => 'Student',
            'phone' => '9888877777',
            'roles_id' => 'stu',
            'departments_id' => $dept->code,
        ]);

        $student = User::create([
            'username' => 'STU_DEPT_01',
            'password' => Hash::make('StudentPass123'),
            'profile_id' => $stuProfile->id,
        ]);

        $response = $this->actingAs($coordinator)
            ->post(route('coordinator.reset-password-by-username'), [
                'username' => 'STU_DEPT_01',
                'new_password' => 'Vignan@2026',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $student->refresh();
        $this->assertTrue(Hash::check('Vignan@2026', $student->password));

        Http::assertSent(function ($request) {
            return $request['to'] === '9888877777'
                && str_contains($request['body'], 'Password: Vignan@2026');
        });
    }

    public function test_sms_controller_send_vsat_endpoint()
    {
        $response = $this->get(route('sms.test.vsat'));

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));

        Http::assertSent(function ($request) {
            return $request['to'] === '9440151948'
                && str_contains($request['body'], 'Username:03141')
                && str_contains($request['body'], 'Password: Vignan123');
        });
    }

    public function test_second_failed_attempt_warns_user_and_forgot_password_modal_dispatches_sms()
    {
        $profile = Profile::create([
            'username' => 'STU_2FAIL_01',
            'first_name' => 'AutoSms',
            'last_name' => 'Student',
            'phone' => '9111122222',
            'roles_id' => 'stu',
        ]);

        $user = User::create([
            'username' => 'STU_2FAIL_01',
            'password' => Hash::make('ActualPass123'),
            'profile_id' => $profile->id,
            'failed_login_attempts' => 0,
        ]);

        // 1st Failed Attempt: Should show 1 attempt left before security warning
        $response1 = $this->from('/')
            ->post(route('authenticate'), [
                'emp_id' => 'STU_2FAIL_01',
                'password' => 'WrongPassOne',
            ]);

        $response1->assertRedirect('/');
        $response1->assertSessionHasErrors('emp_id');
        $this->assertStringContainsString('1 attempt left', session('errors')->first('emp_id'));

        $user->refresh();
        $this->assertEquals(1, $user->failed_login_attempts);

        // 2nd Failed Attempt: Should warn user without auto-wiping password or auto-sending SMS
        $response2 = $this->from('/')
            ->post(route('authenticate'), [
                'emp_id' => 'STU_2FAIL_01',
                'password' => 'WrongPassTwo',
            ]);

        $response2->assertRedirect('/');
        $response2->assertSessionHasErrors('emp_id');
        $response2->assertSessionHas('show_forgot_prompt', true);
        $this->assertStringContainsString('2 failed login attempts', session('errors')->first('emp_id'));
        $this->assertStringContainsString('Forgot Password', session('errors')->first('emp_id'));

        $user->refresh();
        $this->assertEquals(2, $user->failed_login_attempts);
        // Original password still intact
        $this->assertTrue(Hash::check('ActualPass123', $user->password));

        // User opens Forgot Password modal and requests temporary password via SMS
        $dispatchedPassword = null;
        $forgotResponse = $this->from('/')
            ->post(route('password.forgot'), [
                'username' => 'STU_2FAIL_01',
            ]);

        $forgotResponse->assertRedirect('/');
        $forgotResponse->assertSessionHas('forgot_success');
        $this->assertStringContainsString('91******22', session('forgot_success'));

        $user->refresh();
        $this->assertEquals(0, $user->failed_login_attempts);
        $this->assertFalse((bool) $user->requires_password_reset);

        Http::assertSent(function ($request) use (&$dispatchedPassword) {
            if ($request['to'] === '9111122222' && str_contains($request['body'], 'Username:STU_2FAIL_01')) {
                if (preg_match('/Password:\s*(\S+)/', $request['body'], $matches)) {
                    $dispatchedPassword = $matches[1];
                }
                return true;
            }
            return false;
        });

        $this->assertNotNull($dispatchedPassword);

        // Now user enters the temporary password received via SMS and logs in successfully!
        $loginResponse = $this->post(route('authenticate'), [
            'emp_id' => 'STU_2FAIL_01',
            'password' => $dispatchedPassword,
        ]);

        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_locked_user_attempting_login_receives_temporary_password_via_sms()
    {
        $profile = Profile::create([
            'username' => 'STU_LOCKED_01',
            'first_name' => 'Locked',
            'last_name' => 'User',
            'phone' => '9333344444',
            'roles_id' => 'stu',
        ]);

        $user = User::create([
            'username' => 'STU_LOCKED_01',
            'password' => Hash::make('OldPassword123'),
            'profile_id' => $profile->id,
            'failed_login_attempts' => 3,
            'requires_password_reset' => true,
        ]);

        $dispatchedPassword = null;

        // User attempts login while account was locked
        $response = $this->from('/')
            ->post(route('authenticate'), [
                'emp_id' => 'STU_LOCKED_01',
                'password' => 'SomePassword',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('forgot_success');
        $this->assertStringContainsString('93******44', session('forgot_success'));

        $user->refresh();
        $this->assertEquals(0, $user->failed_login_attempts);
        $this->assertFalse((bool) $user->requires_password_reset);

        Http::assertSent(function ($request) use (&$dispatchedPassword) {
            if ($request['to'] === '9333344444' && str_contains($request['body'], 'Username:STU_LOCKED_01')) {
                if (preg_match('/Password:\s*(\S+)/', $request['body'], $matches)) {
                    $dispatchedPassword = $matches[1];
                }
                return true;
            }
            return false;
        });

        $this->assertNotNull($dispatchedPassword);

        // User logs in using the dispatched temporary password
        $loginResponse = $this->post(route('authenticate'), [
            'emp_id' => 'STU_LOCKED_01',
            'password' => $dispatchedPassword,
        ]);

        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
