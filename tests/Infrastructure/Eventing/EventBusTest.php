<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Eventing\EventBus;
use App\Infrastructure\Eventing\EventListener\EventListener;
use App\Tests\ContainerTestCase;
use App\Tests\Infrastructure\Eventing\EventListener\TestEventListener;
use Spatie\Snapshots\MatchesSnapshots;

class EventBusTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private EventBus $eventBus;

    public function testItRegistersAllEventListeners(): void
    {
        $this->assertMatchesJsonSnapshot(array_keys($this->eventBus->getEventListeners()));
    }

    public function testPublish(): void
    {
        $event = new TestEvent(1);
        $eventListenerOne = $this->createMock(EventListener::class);
        $eventListenerTwo = $this->getMockBuilder(EventListener::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMockClassName('TestEventListener')
            ->getMock();

        $eventListenerOne
            ->expects($this->once())
            ->method('isListeningToEvent')
            ->with($event)
            ->willReturn(false);

        $eventListenerOne
            ->expects($this->never())
            ->method('notifyThat');

        $eventListenerTwo
            ->expects($this->once())
            ->method('isListeningToEvent')
            ->with($event)
            ->willReturn(true);

        $eventListenerTwo
            ->expects($this->once())
            ->method('notifyThat')
            ->with($event);

        $eventBus = new EventBus();
        $eventBus->subscribeEventListener($eventListenerOne);
        $eventBus->subscribeEventListener($eventListenerTwo);

        $eventBus->publish($event);
    }

    public function testItShouldThrowOnDuplicateEventSubscriber(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('EventListener "App\Tests\Infrastructure\Eventing\EventListener\TestEventListener" already registered');

        $eventBus = new EventBus();

        $eventBus->subscribeEventListener(new TestEventListener());
        $eventBus->subscribeEventListener(new TestEventListener());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventBus = $this->getContainer()->get(EventBus::class);
    }
}
