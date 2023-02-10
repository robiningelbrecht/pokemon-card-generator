<?php

namespace App\Infrastructure\AMQP;

use App\Infrastructure\AMQP\Queue\Queue;
use PhpAmqpLib\Channel\AMQPChannel;

class AMQPChannelFactory
{
    /** @var AMQPChannel[] */
    private array $channels = [];

    public function __construct(
        private readonly AMQPStreamConnectionFactory $AMQPStreamConnectionFactory
    ) {
    }

    public function getForQueue(Queue $queue, AMQPChannelOptions $options = null): AMQPChannel
    {
        if (!array_key_exists($queue->getName(), $this->channels)) {
            $this->channels[$queue->getName()] = $this->AMQPStreamConnectionFactory->get()->channel();

            $options = $options ?? AMQPChannelOptions::default();

            $this->channels[$queue->getName()]->queue_declare(
                $queue->getName(),
                $options->isPassive(),
                $options->isDurable(),
                $options->isExclusive(),
                $options->isAutoDelete(),
                $options->isNowait(),
                $options->getArguments(),
                $options->getTicket()
            );
            $this->channels[$queue->getName()]->basic_qos(0, 1, false);
        }

        return $this->channels[$queue->getName()];
    }
}
