<?php

namespace App\Tests\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\Eventing\EventListener\ConventionBasedEventListener;
use App\Tests\Infrastructure\Eventing\TestEvent;

#[AsEventListener]
class TestEventListener extends ConventionBasedEventListener
{
    public function reactToTestEvent(TestEvent $event): void
    {
        throw new \RuntimeException('reacted to event!');
    }
}
