<?php

namespace App\Tests;

use App\Infrastructure\Eventing\EventBus;
use App\Infrastructure\Eventing\EventListener\EventListener;

abstract class EventListenerTestCase extends ContainerTestCase
{
    public function testItShouldBeInEventBus(): void
    {
        /** @var EventBus $eventBus */
        $eventBus = $this->getContainer()->get(EventBus::class);

        $this->assertNotEmpty(array_filter(
            $eventBus->getEventListeners(),
            fn (EventListener $eventListener) => $eventListener::class === $this->getEventListener()::class
        ), sprintf('EventListener "%s" not found in EventBus. Did you tag it with an attribute?', $this->getEventListener()::class));
    }

    abstract protected function getEventListener(): EventListener;
}
