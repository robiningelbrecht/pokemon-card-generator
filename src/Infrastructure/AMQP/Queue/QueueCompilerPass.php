<?php

namespace App\Infrastructure\AMQP\Queue;

use App\Infrastructure\Attribute\AsAmqpQueue;
use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;

class QueueCompilerPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(QueueContainer::class);
        foreach ($container->findTaggedWithClassAttribute(AsAmqpQueue::class) as $class) {
            $definition->method('registerQueue', \DI\autowire($class));
        }

        $container->addDefinitions(
            [QueueContainer::class => $definition],
        );
    }
}
