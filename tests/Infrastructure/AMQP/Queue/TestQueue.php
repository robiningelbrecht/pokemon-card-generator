<?php

namespace App\Tests\Infrastructure\AMQP\Queue;

use App\Infrastructure\AMQP\Queue\AmqpQueue;
use App\Infrastructure\AMQP\Worker\Worker;
use App\Infrastructure\Attribute\AsAmqpQueue;
use App\Tests\Infrastructure\AMQP\Worker\TestWorker;
use App\Tests\PausedClock;

#[AsAmqpQueue(name: 'test-queue', numberOfWorkers: 1)]
class TestQueue extends AmqpQueue
{
    public function getWorker(): Worker
    {
        return new TestWorker(PausedClock::on(new \DateTimeImmutable('2022-07-01')));
    }
}
