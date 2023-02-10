<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\Attribute\AsAmqpQueue;
use App\Infrastructure\CQRS\CommandQueue;

#[AsAmqpQueue(name: 'test-command-queue', numberOfWorkers: 1)]
class TestCommandQueue extends CommandQueue
{
}
