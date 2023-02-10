<?php

namespace App\Infrastructure\Eventing;

abstract class AggregateRoot
{
    /** @var \App\Infrastructure\Eventing\DomainEvent[] */
    private array $recordedEvents = [];

    protected function recordThat(DomainEvent $domainEvent): void
    {
        $this->recordedEvents[] = $domainEvent;
    }

    /**
     * @return \App\Infrastructure\Eventing\DomainEvent[]
     */
    public function getRecordedEvents(): array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }
}
