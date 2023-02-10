<?php

namespace App\Domain\Card;

use App\Infrastructure\Attribute\AsAmqpQueue;
use App\Infrastructure\CQRS\CommandQueue;

#[AsAmqpQueue(name: 'generate-card-command-queue', numberOfWorkers: 1)]
class GenerateCardCommandQueue extends CommandQueue
{
}
