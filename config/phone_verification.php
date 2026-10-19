<?php

return [
    'code_length' => (int) env('PHONE_OTP_LENGTH', 6),

    'ttl_minutes' => (int) env('PHONE_OTP_TTL_MINUTES', 10),

    // Wrong guesses allowed per issued code before a new code is required
    'max_attempts' => (int) env('PHONE_OTP_MAX_ATTEMPTS', 5),

    'resend_cooldown_seconds' => (int) env('PHONE_OTP_RESEND_COOLDOWN', 60),

    // Hard cap on sms sent per phone per hour, protects against sms pumping costs
    'max_sends_per_hour' => (int) env('PHONE_OTP_MAX_SENDS_PER_HOUR', 5),

    // Dial code applied to numbers typed in national format such as 0803...
    'default_country_code' => env('PHONE_DEFAULT_COUNTRY_CODE', '234'),

    // Account types that may self register with a phone number
    'allowed_user_types' => ['customer', 'vendor'],
];
