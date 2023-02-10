<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Serialization\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class AggregateRootTest extends TestCase
{
    use MatchesSnapshots;

    public function testRecordAndGet(): void
    {
        $aggregate = new TestAggregate();
        $aggregate->update();
        $aggregate->update();

        $this->assertMatchesJsonSnapshot(Json::encode($aggregate->getRecordedEvents()));
        $this->assertEmpty($aggregate->getRecordedEvents());
    }
}
