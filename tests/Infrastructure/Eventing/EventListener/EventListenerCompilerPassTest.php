<?php

namespace App\Tests\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use App\Infrastructure\Eventing\EventBus;
use App\Infrastructure\Eventing\EventListener\EventListenerCompilerPass;
use DI\Definition\Helper\AutowireDefinitionHelper;
use PHPUnit\Framework\TestCase;

class EventListenerCompilerPassTest extends TestCase
{
    public function testProcess(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $definition = $this->createMock(AutowireDefinitionHelper::class);

        $containerBuilder
            ->expects($this->once())
            ->method('findDefinition')
            ->with(EventBus::class)
            ->willReturn($definition);

        $containerBuilder
            ->expects($this->once())
            ->method('findTaggedWithClassAttribute')
            ->with(AsEventListener::class)
            ->willReturn([TestEventListener::class]);

        $definition
            ->expects($this->once())
            ->method('method')
            ->with('subscribeEventListener', \DI\autowire(TestEventListener::class));

        $containerBuilder
            ->expects($this->once())
            ->method('addDefinitions')
            ->with([EventBus::class => $definition]);

        $compilerPass = new EventListenerCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
