<?php

namespace App\Infrastructure\CQRS\CommandHandler;

use App\Infrastructure\CQRS\DomainCommand;

interface CommandHandler
{
    public function handle(DomainCommand $command): void;
}
