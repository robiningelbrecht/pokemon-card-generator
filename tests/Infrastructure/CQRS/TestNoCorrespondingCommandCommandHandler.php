<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

class TestNoCorrespondingCommandCommandHandler implements CommandHandler
{
    public function handle(DomainCommand $command): void
    {
        // TODO: Implement handle() method.
    }
}
