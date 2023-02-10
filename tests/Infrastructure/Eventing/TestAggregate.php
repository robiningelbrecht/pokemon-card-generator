<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Eventing\AggregateRoot;

class TestAggregate extends AggregateRoot
{
    public function update(): void
    {
        $this->recordThat(new TestEvent(1));
    }
}
