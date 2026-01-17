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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Chatbot Service
    |--------------------------------------------------------------------------
    |
    | Configuration for the AI chatbot microservice that provides RAG-based
    | dental FAQ responses. This service runs on localhost and should not
    | be exposed to the internet.
    |
    */
    'chatbot' => [
        'url' => env('CHATBOT_SERVICE_URL', 'http://localhost:8001'),
        'timeout' => env('CHATBOT_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Teeth Detection AI Service
    |--------------------------------------------------------------------------
    |
    | Configuration for the teeth detection AI service (Flask).
    | Should be moved from hardcoded URL in service classes.
    |
    */
    'ai' => [
        'teeth_detection_url' => env('AI_TEETH_DETECTION_URL', 'http://localhost:5000/detect_teeth'),
        'orthodontics_url' => env('AI_ORTHODONTICS_URL', 'http://localhost:5000/diagnose_ortho'),
        'timeout' => env('AI_SERVICE_TIMEOUT', 180),
    ],

];
