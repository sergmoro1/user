<?php
/**
 * Application configuration shared by all applications unit tests
 */
return [
    'components' => [
        'request' => [
            // it's not recommended to run functional tests with CSRF validation enabled
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
];
