<?php

namespace App\Tests\Infrastructure\CQRS\CommandHandler;

use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandlerCompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use DI\Definition\Helper\AutowireDefinitionHelper;
use PHPUnit\Framework\TestCase;

class CommandHandlerCompilerPassTest extends TestCase
{
    public function testProcess(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $definition = $this->createMock(AutowireDefinitionHelper::class);

        $containerBuilder
            ->expects($this->once())
            ->method('findDefinition')
            ->with(CommandBus::class)
            ->willReturn($definition);

        $containerBuilder
            ->expects($this->once())
            ->method('findTaggedWithClassAttribute')
            ->with(AsCommandHandler::class)
            ->willReturn([CommandHandler::class]);

        $definition
            ->expects($this->once())
            ->method('method')
            ->with('subscribeCommandHandler', \DI\autowire(CommandHandler::class));

        $containerBuilder
            ->expects($this->once())
            ->method('addDefinitions')
            ->with([CommandBus::class => $definition]);

        $compilerPass = new CommandHandlerCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
