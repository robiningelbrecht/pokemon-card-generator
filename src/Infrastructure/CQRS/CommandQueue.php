<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Queue\AmqpQueue;
use App\Infrastructure\AMQP\Worker\Worker;

abstract class CommandQueue extends AmqpQueue
{
    public function __construct(
        AMQPChannelFactory $AMQPChannelFactory,
        private readonly CommandQueueWorker $commandQueueWorker,
    ) {
        parent::__construct($AMQPChannelFactory);
    }

    public function getWorker(): Worker
    {
        return $this->commandQueueWorker;
    }

    public function queue(Envelope $envelope): void
    {
        if (!$envelope instanceof DomainCommand) {
            throw new \RuntimeException(sprintf('Queue "%s" requires a command to be queued, %s given', $this->getName(), $envelope::class));
        }

        parent::queue($envelope);
    }

    public function queueBatch(array $envelopes): void
    {
        foreach ($envelopes as $envelope) {
            if ($envelope instanceof DomainCommand) {
                continue;
            }

            throw new \RuntimeException(sprintf('Queue "%s" requires a command to be queued, %s given', $this->getName(), $envelope::class));
        }

        parent::queueBatch($envelopes);
    }
}
