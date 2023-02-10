<?php

namespace App\Infrastructure\AMQP\Worker;

use Lcobucci\Clock\Clock;

abstract class BaseWorker implements Worker
{
    private const MAX_LIFE_TIME_INTERVAL = 'PT1H';

    private int $counter = 0;
    private \DateTimeImmutable $maxLifeTimeDateTime;

    public function __construct(
        private readonly Clock $clock
    ) {
        $this->maxLifeTimeDateTime = $this->clock->now()->add($this->getMaxLifeTimeInterval());
    }

    public function getMaxIterations(): int
    {
        return 1000;
    }

    public function maxIterationsReached(): bool
    {
        return $this->counter++ >= $this->getMaxIterations();
    }

    public function getMaxLifeTime(): \DateTimeImmutable
    {
        return $this->maxLifeTimeDateTime;
    }

    public function getMaxLifeTimeInterval(): \DateInterval
    {
        return new \DateInterval(self::MAX_LIFE_TIME_INTERVAL);
    }

    public function maxLifeTimeReached(): bool
    {
        return $this->clock->now() >= $this->maxLifeTimeDateTime;
    }
}
