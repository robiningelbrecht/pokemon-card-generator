<?php

namespace App\Infrastructure\AMQP\Queue\DelayedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\AMQPChannelOptions;
use App\Infrastructure\AMQP\Queue\AmqpQueue;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\Worker;
use PhpAmqpLib\Channel\AMQPChannel;

class DelayedQueue extends AmqpQueue
{
    private const X_DEAD_LETTER_EXCHANGE = 'dlx';

    public function __construct(
        private readonly Queue $queue,
        private readonly int $delayInSeconds,
        private readonly AMQPChannelFactory $AMQPChannelFactory,
    ) {
        if ($this->delayInSeconds < 1) {
            throw new \InvalidArgumentException('Delay cannot be less than 1 second');
        }
        parent::__construct($AMQPChannelFactory);
    }

    public function getName(): string
    {
        return 'delayed-'.$this->delayInSeconds.'s-'.$this->queue->getName();
    }

    public function getWorker(): Worker
    {
        throw new \RuntimeException('Delayed queues do not have workers');
    }

    protected function getChannel(): AMQPChannel
    {
        $options = new AMQPChannelOptions(false, true, false, false, false, [
            'x-dead-letter-exchange' => ['S', self::X_DEAD_LETTER_EXCHANGE],
            'x-dead-letter-routing-key' => ['S', $this->queue->getName()],
            'x-message-ttl' => ['I', $this->delayInSeconds * 1000],
            'x-expires' => ['I', $this->delayInSeconds * 1000 + 100000], // Keep the Q for 100s after the last message,
        ]);

        return $this->AMQPChannelFactory->getForQueue($this, $options);
    }

    public function getNumberOfConsumers(): int
    {
        return 0;
    }
}
