<?php

namespace App\Tests\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Eventing\DomainEvent;
use App\Tests\Infrastructure\Eventing\TestEvent;
use PHPUnit\Framework\TestCase;

class ConventionBasedEventListenerTest extends TestCase
{
    public function testNotifyThatForEventListeningTo(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('reacted to event!');

        $eventListener = new TestEventListener();
        $eventListener->notifyThat(new TestEvent(1));
    }

    public function testNotifyThatForEventNotListeningTo(): void
    {
        $eventListener = new TestEventListener();
        $eventListener->notifyThat($this->createMock(DomainEvent::class));

        $this->addToAssertionCount(1);
    }

    public function testIsListeningToEvent(): void
    {
        $eventListener = new TestEventListener();

        $this->assertTrue($eventListener->isListeningToEvent(new TestEvent(1)));
        $this->assertFalse($eventListener->isListeningToEvent($this->createMock(DomainEvent::class)));
    }

    public function testItShouldThrowOnMissingAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Event listener App\Tests\Infrastructure\Eventing\EventListener\TestEventListenerNotTagged not tagged with attribute');

        $eventListener = new TestEventListenerNotTagged();
    }
}
