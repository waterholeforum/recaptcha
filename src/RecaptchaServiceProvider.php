<?php

namespace Waterhole\Recaptcha;

use Waterhole\Extend;
use Waterhole\Recaptcha\Fields\Recaptcha;

class RecaptchaServiceProvider extends Extend\ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/recaptcha.php', 'waterhole.recaptcha');

        $this->extend(function (Extend\Forms\RegistrationForm $form) {
            $form->add(Recaptcha::class, 'recaptcha');
        });
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'waterhole-recaptcha');

        $this->publishes(
            [__DIR__ . '/../config/recaptcha.php' => config_path('waterhole/recaptcha.php')],
            'waterhole-recaptcha-config',
        );
    }
}
