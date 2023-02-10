<?php

namespace App\Tests\Infrastructure\AMQP\RunUnitTest;

use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

class RunUnitTestCommandHandler implements CommandHandler
{
    public function handle(DomainCommand $command): void
    {
        // TODO: Implement handle() method.
    }
}
