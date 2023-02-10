<?php

namespace App\Infrastructure\AMQP\Queue\DelayedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\Queue;

class DelayedQueueFactory
{
    public function __construct(
        private readonly AMQPChannelFactory $AMQPChannelFactory,
    ) {
    }

    public function buildWithDelayForQueue(int $delayInSeconds, Queue $queue): Queue
    {
        return new DelayedQueue(
            $queue,
            $delayInSeconds,
            $this->AMQPChannelFactory,
        );
    }
}
