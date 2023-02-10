<?php

namespace App\Infrastructure\Console;

use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use Symfony\Component\Console\Attribute\AsCommand;

class ConsoleCommandCompilerPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ConsoleCommandContainer::class);
        foreach ($container->findTaggedWithClassAttribute(AsCommand::class, 'src/Console') as $class) {
            $definition->method('registerCommand', \DI\autowire($class));
        }

        $container->addDefinitions(
            [ConsoleCommandContainer::class => $definition],
        );
    }
}
