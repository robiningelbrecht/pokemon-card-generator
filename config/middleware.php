<?php

use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\ErrorRenderer;
use Slim\App;

return function (App $app) {
    $settings = $app->getContainer()->get(Settings::class);
    // Add Error middleware.
    $errorMiddleware = $app->addErrorMiddleware(
        $settings->get('slim.displayErrorDetails'),
        $settings->get('slim.logErrors'),
        $settings->get('slim.logErrorDetails'),
    );

    /** @var \Slim\Handlers\ErrorHandler $errorHandler */
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('text/html', ErrorRenderer::class);
};
