<?php

namespace App\Infrastructure\AMQP\Queue;

use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\AMQP\Worker\Worker;

interface Queue
{
    public function getName(): string;

    public function getWorker(): Worker;

    public function getNumberOfConsumers(): int;

    public function queue(Envelope $envelope): void;

    /**
     * @param array<Envelope> $envelopes
     */
    public function queueBatch(array $envelopes): void;
}
