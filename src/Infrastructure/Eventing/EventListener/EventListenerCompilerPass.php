<?php

namespace App\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use App\Infrastructure\Eventing\EventBus;

class EventListenerCompilerPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(EventBus::class);
        foreach ($container->findTaggedWithClassAttribute(AsEventListener::class) as $class) {
            $definition->method('subscribeEventListener', \DI\autowire($class));
        }

        $container->addDefinitions(
            [EventBus::class => $definition],
        );
    }
}
