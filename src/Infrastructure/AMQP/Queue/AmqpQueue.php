<?php

namespace App\Infrastructure\AMQP\Queue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\Attribute\AsAmqpQueue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AmqpQueue implements Queue
{
    private ?AsAmqpQueue $amqpQueueAttribute = null;

    public function __construct(
        private readonly AMQPChannelFactory $AMQPChannelFactory
    ) {
        if ($attribute = (new \ReflectionClass($this))->getAttributes(AsAmqpQueue::class)) {
            $this->amqpQueueAttribute = $attribute[0]->newInstance();
        }
    }

    public function getName(): string
    {
        if (!$this->amqpQueueAttribute) {
            throw new \RuntimeException('AsAmqpQueue attribute not set');
        }

        return $this->amqpQueueAttribute->getName();
    }

    public function getNumberOfConsumers(): int
    {
        if (!$this->amqpQueueAttribute) {
            throw new \RuntimeException('AsAmqpQueue attribute not set');
        }

        return $this->amqpQueueAttribute->getNumberOfWorkers();
    }

    public function queue(Envelope $envelope): void
    {
        $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
        $message = new AMQPMessage(serialize($envelope), $properties);
        $this->getChannel()->basic_publish($message, '', $this->getName());
    }

    public function queueBatch(array $envelopes): void
    {
        if (empty($envelopes)) {
            return;
        }

        if (!empty(array_filter($envelopes, function ($envelope) {
            return !($envelope instanceof Envelope);
        }))) {
            throw new \RuntimeException(sprintf('All envelopes need to implement %s', Envelope::class));
        }

        $channel = $this->getChannel();

        foreach ($envelopes as $envelope) {
            $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
            $message = new AMQPMessage(serialize($envelope), $properties);
            $channel->batch_basic_publish($message, '', $this->getName());
        }
        $channel->publish_batch();
    }

    protected function getChannel(): AMQPChannel
    {
        return $this->AMQPChannelFactory->getForQueue($this);
    }
}
