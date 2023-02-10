<?php

namespace App\Infrastructure\AMQP;

class AMQPChannelOptions
{
    // See PhpAmqpLib\Channel\AMQPChannel::queue_declare() for explanation for params.
    public function __construct(
        private readonly bool $passive = false,
        private readonly bool $durable = false,
        private readonly bool $exclusive = false,
        private readonly bool $autoDelete = true,
        private readonly bool $nowait = false,
        /** @var array<mixed> $arguments */
        private readonly array $arguments = [],
        private readonly ?int $ticket = null
    ) {
    }

    public function isPassive(): bool
    {
        return $this->passive;
    }

    public function isDurable(): bool
    {
        return $this->durable;
    }

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    public function isNowait(): bool
    {
        return $this->nowait;
    }

    /**
     * @return array<mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getTicket(): ?int
    {
        return $this->ticket;
    }

    public static function default(): self
    {
        return new self(false, true, false, false);
    }
}
