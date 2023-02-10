<?php

use App\Infrastructure\Console\ConsoleCommandCompilerPass;
use App\Infrastructure\CQRS\CommandHandler\CommandHandlerCompilerPass;
use App\Infrastructure\Eventing\EventListener\EventListenerCompilerPass;

// Compiler passes give you an opportunity to manipulate other service
// definitions that have been registered with the service container.
// It's a mechanism copied from Symfony:
// https://symfony.com/doc/current/service_container/compiler_passes.html
//
// The existing compiler passes are mainly used to auto discover
// classes tagged with a class attribute.

return [
    // Compiler pass to auto discover console commands.
    new ConsoleCommandCompilerPass(),
    // Compiler pass to auto discover command handlers.
    new CommandHandlerCompilerPass(),
    // Compiler pass to auto discover event listeners.
    new EventListenerCompilerPass(),
];
