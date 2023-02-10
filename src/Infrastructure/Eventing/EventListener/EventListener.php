<?php

namespace App\Infrastructure\Eventing\EventListener;

use App\Infrastructure\Eventing\DomainEvent;

interface EventListener
{
    public function notifyThat(DomainEvent $event): void;

    public function isListeningToEvent(DomainEvent $event): bool;
}
