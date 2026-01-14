<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site/Secret Keys
    |--------------------------------------------------------------------------
    |
    | Create keys at https://www.google.com/recaptcha/admin.
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Score Threshold
    |--------------------------------------------------------------------------
    |
    | Minimum score required to accept a registration.
    | 1.0 is very likely a good interaction, 0.0 is very likely a bot
    |
    */

    'score_threshold' => 0.5,
];
