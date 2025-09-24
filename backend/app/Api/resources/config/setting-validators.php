<?php

return [
    Api\Settings\Validators\MailCredentials\MailCredentialsValidator::class,
    Api\Settings\Validators\GoogleLoginValidator::class,
    Api\Settings\Validators\FacebookLoginValidator::class,
    Api\Settings\Validators\TwitterLoginValidator::class,
    Api\Settings\Validators\StorageCredentialsValidator::class,
    Api\Settings\Validators\CacheConfigValidator::class,
    Api\Settings\Validators\AnalyticsCredentialsValidator::class,
    Api\Settings\Validators\QueueCredentialsValidator::class,
    Api\Settings\Validators\LoggingCredentialsValidator::class,
    Api\Settings\Validators\RecaptchaCredentialsValidator::class,
    Api\Settings\Validators\PaypalCredentialsValidator::class,
    Api\Settings\Validators\StripeCredentialsValidator::class,
    Api\Settings\Validators\RealtimeCredentialsValidator::class,
    Api\Settings\Validators\SearchConfigValidator::class,
    Api\Settings\Validators\StaticFileDeliveryValidator::class,
];

