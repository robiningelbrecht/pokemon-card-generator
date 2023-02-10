<?php

namespace App\Tests\Infrastructure\Eventing;

use App\Infrastructure\Serialization\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class DomainEventTest extends TestCase
{
    use MatchesSnapshots;

    public function testGetShortClassName(): void
    {
        $this->assertEquals('TestEvent', (new TestEvent(1))->getShortClassName());
    }

    public function testJsonSerialize(): void
    {
        $this->assertMatchesJsonSnapshot(Json::encode(new TestEvent(1)));
    }
}
