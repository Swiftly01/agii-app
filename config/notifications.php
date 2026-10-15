<?php

return [

    /*
    | Queue used by the notification fan-out. Run a dedicated worker so a big
    | announcement never delays emails/payments on the default queue:
    |   php artisan queue:work --queue=notifications,default
    */
    'queue' => env('NOTIFICATIONS_QUEUE', 'notifications'),

    // Users handled per fan-out job.
    'chunk_size' => (int) env('NOTIFICATIONS_CHUNK_SIZE', 500),

    'push' => [
        // Seconds the push service keeps an undelivered message (offline devices).
        'ttl' => (int) env('NOTIFICATIONS_PUSH_TTL', 86400),
        'urgency' => 'normal',
        // Paths relative to /public.
        'icon' => 'images/icons/launchericon-192x192.png',
        'badge' => 'images/icons/launchericon-96x96.png',
    ],

];
