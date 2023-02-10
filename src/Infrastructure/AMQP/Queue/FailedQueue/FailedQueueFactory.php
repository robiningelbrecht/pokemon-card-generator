<?php

namespace App\Infrastructure\AMQP\Queue\FailedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\Queue;

class FailedQueueFactory
{
    public function __construct(
        private AMQPChannelFactory $AMQPChannelFactory
    ) {
    }

    public function buildFor(Queue $queue): Queue
    {
        return new FailedQueue(
            $queue,
            $this->AMQPChannelFactory
        );
    }
}
