<?php

return [
    // Active gateway: termii, twilio or log (log never sends, local use only)
    'default' => env('SMS_DRIVER', 'log'),

    // Queue that carries outbound sms so provider latency never blocks web requests
    'queue' => env('SMS_QUEUE', 'sms'),

    'drivers' => [
        'termii' => [
            'api_key' => env('TERMII_API_KEY'),
            'sender_id' => env('TERMII_SENDER_ID'),
            // Use dnd so messages also reach numbers with do-not-disturb enabled
            'channel' => env('TERMII_CHANNEL', 'dnd'),
            'base_url' => env('TERMII_BASE_URL', 'https://api.ng.termii.com'),
            'timeout' => (int) env('TERMII_TIMEOUT', 10),
        ],

        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_FROM'),
            // When set, this takes priority over the from number
            'messaging_service_sid' => env('TWILIO_MESSAGING_SERVICE_SID'),
            'timeout' => (int) env('TWILIO_TIMEOUT', 10),
        ],
    ],
];
