<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Eventing\DomainEvent;

class TestEvent extends DomainEvent
{
    public function __construct(
        protected string $id,
    ) {
    }
}
