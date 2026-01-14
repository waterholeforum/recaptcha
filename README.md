# Waterhole reCAPTCHA

**reCAPTCHA checks for Waterhole registration.**

When enabled, registration requires a valid reCAPTCHA v3 response. If verification fails, the registration is rejected with a validation error.

## Installation

```
composer require waterhole/recaptcha
```

## Configuration

Publish the config:

```
php artisan vendor:publish --tag=waterhole-recaptcha-config
```

Then set your keys in `.env`:

```
RECAPTCHA_SITE_KEY=...
RECAPTCHA_SECRET_KEY=...
```

This extension runs automatically on registration once installed.

## Scoring

By default, a score threshold of `0.5` is enforced. To change or disable it, edit
`extensions/recaptcha/config/recaptcha.php` and set `score_threshold` to your
preferred value.
