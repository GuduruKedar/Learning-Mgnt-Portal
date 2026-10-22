<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'code_execution' => [
        'wandbox_url' => env('WANDBOX_API_URL', 'https://wandbox.org/api/compile.json'),
        'timeout' => env('CODE_EXECUTION_TIMEOUT', 25),
        'connect_timeout' => env('CODE_EXECUTION_CONNECT_TIMEOUT', 6),
        'java_timeout' => env('CODE_EXECUTION_JAVA_TIMEOUT', 25),
        'c_timeout' => env('CODE_EXECUTION_C_TIMEOUT', 18),
        'python_timeout' => env('CODE_EXECUTION_PYTHON_TIMEOUT', 15),
        'local_python_enabled' => env('CODE_EXECUTION_LOCAL_PYTHON', true),
        'local_python_timeout' => env('CODE_EXECUTION_LOCAL_PYTHON_TIMEOUT', 4.0),
    ],

];
