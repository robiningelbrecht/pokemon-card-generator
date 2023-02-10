<?php

use App\Infrastructure\DependencyInjection\ContainerFactory;
use DI\Bridge\Slim\Bridge;

$container = ContainerFactory::create();
$app = Bridge::create($container);

// Register routes
(require __DIR__.'/routes.php')($app);
// Register middleware
(require __DIR__.'/middleware.php')($app);

return $app;
