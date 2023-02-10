<?php

namespace App\Infrastructure\Attribute;

use App\Infrastructure\Eventing\EventListener\EventListenerType;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsEventListener
{
    public function __construct(
        private readonly EventListenerType $type = EventListenerType::PROCESS_MANAGER,
    ) {
    }

    public function getType(): EventListenerType
    {
        return $this->type;
    }
}
