<?php

namespace App\Tests\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Eventing\EventListener\EventListenerType;
use PHPUnit\Framework\TestCase;

class EventListenerTypeTest extends TestCase
{
    public function testGetEventProcessingMethodPrefix(): void
    {
        $this->assertEquals('reactTo', EventListenerType::PROCESS_MANAGER->getEventProcessingMethodPrefix());
        $this->assertEquals('project', EventListenerType::PROJECTOR->getEventProcessingMethodPrefix());
    }
}
