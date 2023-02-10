<?php

namespace App\Infrastructure\Eventing;

use App\Infrastructure\Eventing\EventListener\EventListener;

class EventBus
{
    /** @var EventListener[] */
    private array $eventListeners = [];
    /** @var DomainEvent[] */
    private array $queue = [];
    private bool $isPublishing = false;

    public function subscribeEventListener(EventListener $eventListener): void
    {
        if (array_key_exists($eventListener::class, $this->eventListeners)) {
            throw new \RuntimeException(sprintf('EventListener "%s" already registered', $eventListener::class));
        }
        $this->eventListeners[$eventListener::class] = $eventListener;
    }

    /**
     * @return EventListener[]
     */
    public function getEventListeners(): array
    {
        return $this->eventListeners;
    }

    public function publish(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->queue[] = $domainEvent;
        }

        if (!$this->isPublishing) {
            $this->isPublishing = true;

            try {
                while ($domainEvent = array_shift($this->queue)) {
                    foreach ($this->eventListeners as $eventListener) {
                        if (!$eventListener->isListeningToEvent($domainEvent)) {
                            continue;
                        }
                        $eventListener->notifyThat($domainEvent);
                    }
                }
            } finally {
                $this->isPublishing = false;
            }
        }
    }
}
