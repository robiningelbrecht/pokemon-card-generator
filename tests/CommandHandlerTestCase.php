<?php

namespace App\Tests;

use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;

abstract class CommandHandlerTestCase extends ContainerTestCase
{
    public function testItShouldBeInCommandBus(): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->getContainer()->get(CommandBus::class);

        $this->assertNotEmpty(array_filter(
            $commandBus->getCommandHandlers(),
            fn (CommandHandler $commandHandler) => $commandHandler::class === $this->getCommandHandler()::class
        ), sprintf('CommandHandler "%s" not found in CommandBus. Did you tag it with an attribute?', $this->getCommandHandler()::class));
    }

    abstract protected function getCommandHandler(): CommandHandler;
}
