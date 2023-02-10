<?php

namespace App\Infrastructure\CQRS\CommandHandler;

use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;

class CommandHandlerCompilerPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(CommandBus::class);
        foreach ($container->findTaggedWithClassAttribute(AsCommandHandler::class) as $class) {
            $definition->method('subscribeCommandHandler', \DI\autowire($class));
        }

        $container->addDefinitions(
            [CommandBus::class => $definition],
        );
    }
}
