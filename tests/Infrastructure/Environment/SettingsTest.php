<?php

namespace App\Tests\Infrastructure\Environment;

use App\Infrastructure\Environment\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    public function testGet(): void
    {
        $settings = new Settings(['key' => ['key2' => ['key3' => 'value']]]);

        $this->assertEquals(['key2' => ['key3' => 'value']], $settings->get('key'));
        $this->assertEquals(['key3' => 'value'], $settings->get('key.key2'));
        $this->assertEquals('value', $settings->get('key.key2.key3'));
    }

    public function testGetItShouldThrowWhenInvalid(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Trying to fetch invalid setting "key.key2.key3"');

        $settings = new Settings([]);
        $this->assertEquals('value', $settings->get('key.key2.key3'));
    }
}
