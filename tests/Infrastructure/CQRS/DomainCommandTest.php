<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\Serialization\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class DomainCommandTest extends TestCase
{
    use MatchesSnapshots;

    public function testMetaData(): void
    {
        $command = new TestCommand();

        $command->setMetaData(['key' => 'value']);
        $this->assertMatchesJsonSnapshot(Json::encode($command));
        $command->setMetaData(['key' => 'value override', 'key2' => 'value']);
        $this->assertMatchesJsonSnapshot(Json::encode($command));
    }
}
