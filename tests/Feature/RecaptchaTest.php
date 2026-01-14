<?php

use Illuminate\Support\Facades\Http;
use Waterhole\Models\User;

test('it blocks registrations below the score threshold', function () {
    config([
        'waterhole.recaptcha.site_key' => 'site-key',
        'waterhole.recaptcha.secret_key' => 'secret-key',
        'waterhole.recaptcha.score_threshold' => 0.5,
    ]);

    Http::fake([
        '*' => Http::response([
            'success' => true,
            'score' => 0.1,
            'action' => 'register',
        ]),
    ]);

    $response = $this
        ->post(route('waterhole.register.submit'), [
            'name' => 'Spammer',
            'email' => 'spam@example.com',
            'password' => 'Password123!',
            'g-recaptcha-response' => 'token',
        ]);

    $response->assertSessionHasErrors('recaptcha');

    expect(User::count())->toBe(0);
});
