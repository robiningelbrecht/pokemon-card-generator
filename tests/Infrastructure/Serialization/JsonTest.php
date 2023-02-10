<?php

namespace App\Tests\Infrastructure\Serialization;

use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Serialization\Json;
use PHPUnit\Framework\TestCase;
use Safe\Exceptions\JsonException;
use Spatie\Snapshots\MatchesSnapshots;

class JsonTest extends TestCase
{
    use MatchesSnapshots;

    public function testEncodeDecode(): void
    {
        $array = ['random' => ['array' => ['with', 'children']]];

        $encoded = Json::encode($array);
        $this->assertMatchesJsonSnapshot($encoded);

        $this->assertEquals($array, Json::decode($encoded));
    }

    public function testEncodeItShouldThrowWhenInvalidJson(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Type is not supported: NULL');

        $fp = fopen(Settings::getAppRoot().'/tests/test.txt', 'w');
        Json::encode($fp);
    }
}
