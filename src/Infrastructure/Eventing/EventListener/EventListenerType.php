<?php

namespace App\Infrastructure\Eventing\EventListener;

enum EventListenerType
{
    case PROJECTOR;
    case PROCESS_MANAGER;

    public function getEventProcessingMethodPrefix(): string
    {
        return match ($this) {
            EventListenerType::PROCESS_MANAGER => 'reactTo',
            EventListenerType::PROJECTOR => 'project',
        };
    }
}
