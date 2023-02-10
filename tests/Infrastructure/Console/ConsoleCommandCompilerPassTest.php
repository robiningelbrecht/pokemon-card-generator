<?php

namespace App\Tests\Infrastructure\Console;

use App\Infrastructure\Console\ConsoleCommandCompilerPass;
use App\Infrastructure\Console\ConsoleCommandContainer;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use DI\Definition\Helper\AutowireDefinitionHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

class ConsoleCommandCompilerPassTest extends TestCase
{
    public function testProcess(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $definition = $this->createMock(AutowireDefinitionHelper::class);

        $containerBuilder
            ->expects($this->once())
            ->method('findDefinition')
            ->with(ConsoleCommandContainer::class)
            ->willReturn($definition);

        $containerBuilder
            ->expects($this->once())
            ->method('findTaggedWithClassAttribute')
            ->with(AsCommand::class)
            ->willReturn([Command::class]);

        $definition
            ->expects($this->once())
            ->method('method')
            ->with('registerCommand', \DI\autowire(Command::class));

        $containerBuilder
            ->expects($this->once())
            ->method('addDefinitions')
            ->with([ConsoleCommandContainer::class => $definition]);

        $compilerPass = new ConsoleCommandCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
