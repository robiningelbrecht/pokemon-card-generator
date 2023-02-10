<?php

namespace App\Infrastructure\AMQP\Queue\FailedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\AmqpQueue;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\Worker;

class FailedQueue extends AmqpQueue
{
    public function __construct(
        private readonly Queue $queue,
        AMQPChannelFactory $AMQPChannelFactory
    ) {
        parent::__construct($AMQPChannelFactory);
    }

    public function getName(): string
    {
        return $this->queue->getName().'-failed';
    }

    public function getWorker(): Worker
    {
        throw new \RuntimeException('Failed queues do not have workers');
    }

    public function getNumberOfConsumers(): int
    {
        return 0;
    }
}
