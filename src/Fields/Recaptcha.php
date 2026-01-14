<?php

namespace Waterhole\Recaptcha\Fields;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;

class Recaptcha extends Field
{
    public function render(): string
    {
        $siteKey = config('waterhole.recaptcha.site_key');

        if (!$siteKey) {
            return '';
        }

        return <<<'blade'
            <script src="https://www.google.com/recaptcha/api.js"></script>
            <script>
                window.addEventListener('turbo:load', () => {
                    const form = document.querySelector('form');

                    if (!form) {
                        return;
                    }

                    const submit = form.querySelector('button[type="submit"]');

                    if (!submit) {
                        return;
                    }

                    window.onRecaptchaSubmit = () => {
                        form.submit();
                    };

                    submit.classList.add('g-recaptcha');
                    submit.dataset.sitekey = @json(config('waterhole.recaptcha.site_key'));
                    submit.dataset.callback = 'onRecaptchaSubmit';
                    submit.dataset.action = 'register';

                    grecaptcha.render(submit);
                }, { once: true });
            </script>
        blade;
    }

    public function validating(Validator $validator): void
    {
        if (!config('waterhole.recaptcha.site_key') || !config('waterhole.recaptcha.secret_key')) {
            return;
        }

        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $json = Http::asForm()
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('waterhole.recaptcha.secret_key'),
                    'response' => request()->input('g-recaptcha-response'),
                    'remoteip' => request()->ip(),
                ])
                ->json();

            if (empty($json['success'])) {
                $validator->errors()->add('recaptcha', __('waterhole-recaptcha::messages.failed'));
                return;
            }

            $scoreThreshold = config('waterhole.recaptcha.score_threshold', 0.5);
            if ($json['score'] < (float) $scoreThreshold) {
                $validator->errors()->add('recaptcha', __('waterhole-recaptcha::messages.failed'));
            }
        });
    }
}
