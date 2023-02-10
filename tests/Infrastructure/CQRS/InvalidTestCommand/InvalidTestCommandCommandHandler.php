<?php

namespace App\Tests\Infrastructure\CQRS\InvalidTestCommand;

use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

class InvalidTestCommandCommandHandler implements CommandHandler
{
    public function handle(DomainCommand $command): void
    {
        // TODO: Implement handle() method.
    }
}
