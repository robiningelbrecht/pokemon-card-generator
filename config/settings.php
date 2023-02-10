<?php

use App\Infrastructure\Environment\Settings;

return [
    'slim' => [
        // Returns a detailed HTML page with error details and
        // a stack trace. Should be disabled in production.
        'displayErrorDetails' => $_ENV['DISPLAY_ERROR_DETAILS'],
        // Whether to display errors on the internal PHP log or not.
        'logErrors' => $_ENV['LOG_ERRORS'],
        // If true, display full errors with message and stack trace on the PHP log.
        // If false, display only "Slim Application Error" on the PHP log.
        // Doesn't do anything when 'logErrors' is false.
        'logErrorDetails' => $_ENV['LOG_ERROR_DETAILS'],
        // Path where Slim will cache the container, compiler passes, ...
        'cache_dir' => Settings::getAppRoot().'/var/cache/slim',
    ],
];
