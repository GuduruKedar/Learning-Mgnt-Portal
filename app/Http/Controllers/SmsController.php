<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;

class SmsController extends Controller
{
    /**
     * Test sending credentials SMS via JioCX DLT gateway.
     */
    public function sendVsat(SmsService $sms)
    {
        $username = '03141';
        $password = 'Vignan123';

        $templateId = env('JIOCX_DLT_TEMPLATE_ID_Create_Account');
        if (!$templateId) {
            return response()->json(['error' => 'Template ID missing in .env'], 500);
        }

        $msg = "Dear Staff/Student,\n\n"
             . "An LMS mail id has been created. Please login and change your password immediately once you receive credentials. Here are the credentials for your account.\n"
             . "Username:{$username} and\n"
             . "Password: {$password}\n"
             . "Login through the link below.\n\n"
             . "-- Vignan University.";

        return response()->json(
            $sms->send('9440151948', $msg, $templateId)
        );
    }
}
