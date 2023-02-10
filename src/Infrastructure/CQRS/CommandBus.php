<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\CQRS\CommandHandler\CanNotRegisterCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;

class CommandBus
{
    private const COMMAND_HANDLER_SUFFIX = 'CommandHandler';

    /** @var CommandHandler[] */
    private array $commandHandlers = [];

    public function dispatch(DomainCommand $command): void
    {
        $this->getHandlerForCommand($command)->handle($command);
    }

    public function subscribeCommandHandler(CommandHandler $commandHandler): void
    {
        $this->guardThatFqcnEndsInCommandHandler($commandHandler::class);
        $this->guardThatThereIsACorrespondingCommand($commandHandler);

        $commandFqcn = str_replace(self::COMMAND_HANDLER_SUFFIX, '', $commandHandler::class);
        $this->commandHandlers[$commandFqcn] = $commandHandler;
    }

    /**
     * @return CommandHandler[]
     */
    public function getCommandHandlers(): array
    {
        return $this->commandHandlers;
    }

    private function getHandlerForCommand(DomainCommand $command): CommandHandler
    {
        return $this->commandHandlers[$command::class] ??
            throw new \RuntimeException(sprintf('CommandHandler for command "%s" not subscribed to this bus', $command::class));
    }

    private function guardThatFqcnEndsInCommandHandler(string $fqcn): void
    {
        if (str_ends_with($fqcn, self::COMMAND_HANDLER_SUFFIX)) {
            return;
        }

        throw new CanNotRegisterCommandHandler(sprintf('Fqcn "%s" does not end with "CommandHandler"', $fqcn));
    }

    private function guardThatThereIsACorrespondingCommand(CommandHandler $commandHandler): void
    {
        $commandFqcn = str_replace(self::COMMAND_HANDLER_SUFFIX, '', $commandHandler::class);
        if (!class_exists($commandFqcn)) {
            throw new CanNotRegisterCommandHandler(sprintf('No corresponding command for commandHandler "%s" found', $commandHandler::class));
        }
        if (str_ends_with($commandFqcn, 'Command')) {
            throw new CanNotRegisterCommandHandler('Command name cannot end with "command"');
        }
    }
}
