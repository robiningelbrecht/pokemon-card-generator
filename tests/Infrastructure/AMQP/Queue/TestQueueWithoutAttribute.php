<?php

namespace App\Tests\Infrastructure\AMQP\Queue;

use App\Infrastructure\AMQP\Queue\AmqpQueue;
use App\Infrastructure\AMQP\Worker\Worker;
use App\Tests\Infrastructure\AMQP\Worker\TestWorker;
use App\Tests\PausedClock;

class TestQueueWithoutAttribute extends AmqpQueue
{
    public function getWorker(): Worker
    {
        return new TestWorker(PausedClock::on(new \DateTimeImmutable('2022-07-01')));
    }
}
