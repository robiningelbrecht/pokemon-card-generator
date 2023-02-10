<?php

namespace App\Infrastructure\AMQP\Worker;

use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Queue\Queue;
use PhpAmqpLib\Message\AMQPMessage;

interface Worker
{
    public function getName(): string;

    public function processMessage(Envelope $envelope, AMQPMessage $message): void;

    public function processFailure(Envelope $envelope, AMQPMessage $message, \Throwable $exception, Queue $queue): void;

    public function maxIterationsReached(): bool;

    public function maxLifeTimeReached(): bool;

    public function getMaxIterations(): int;

    public function getMaxLifeTime(): \DateTimeImmutable;

    public function getMaxLifeTimeInterval(): \DateInterval;
}
