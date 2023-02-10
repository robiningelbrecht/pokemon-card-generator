<?php

namespace App\Tests\Infrastructure\AMQP\Worker;

use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\BaseWorker;
use Lcobucci\Clock\Clock;
use PhpAmqpLib\Message\AMQPMessage;

class TestWorker extends BaseWorker
{
    public function __construct(
        Clock $clock,
    ) {
        parent::__construct($clock);
    }

    public function getName(): string
    {
        return 'test-worker';
    }

    public function processMessage(Envelope $envelope, AMQPMessage $message): void
    {
        // TODO: Implement processMessage() method.
    }

    public function processFailure(Envelope $envelope, AMQPMessage $message, \Throwable $exception, Queue $queue): void
    {
        // TODO: Implement processFailure() method.
    }
}
