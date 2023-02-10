<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Queue\FailedQueue\FailedQueueFactory;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\BaseWorker;
use Lcobucci\Clock\Clock;
use PhpAmqpLib\Message\AMQPMessage;

class CommandQueueWorker extends BaseWorker
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly FailedQueueFactory $failedQueueFactory,
        Clock $clock
    ) {
        parent::__construct($clock);
    }

    public function getName(): string
    {
        return 'command-queue-worker';
    }

    public function processMessage(Envelope $envelope, AMQPMessage $message): void
    {
        /** @var \App\Infrastructure\CQRS\DomainCommand $command */
        $command = $envelope;
        $this->commandBus->dispatch($command);
    }

    public function processFailure(Envelope $envelope, AMQPMessage $message, \Throwable $exception, Queue $queue): void
    {
        /** @var \App\Infrastructure\CQRS\DomainCommand $command */
        $command = $envelope;
        $command->setMetaData([
            'exceptionMessage' => $exception->getMessage(),
            'traceAsString' => $exception->getTraceAsString(),
        ]);

        $this->failedQueueFactory->buildFor($queue)->queue($command);
    }
}
