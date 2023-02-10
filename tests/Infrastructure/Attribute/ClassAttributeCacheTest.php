<?php

namespace App\Tests\Infrastructure\Attribute;

use App\Infrastructure\Attribute\AsAmqpQueue;
use App\Infrastructure\Attribute\ClassAttributeCache;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Serialization\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ClassAttributeCacheTest extends TestCase
{
    use MatchesSnapshots;

    private ClassAttributeCache $classAttributeCache;
    private string $dir;

    public function testGetItShouldThrowIfNotExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cache not set for AsAmqpQueue');

        $this->classAttributeCache->get();
    }

    public function testCompile(): void
    {
        $this->assertFalse($this->classAttributeCache->exists());
        $this->classAttributeCache->compile(['classOne', 'classTwo']);
        $this->classAttributeCache->compile(['classOne', 'classTwo']);
        $this->assertTrue($this->classAttributeCache->exists());

        $this->assertStringContainsString('tests/Infrastructure/Attribute/cache/AsAmqpQueue.php', $this->classAttributeCache->get());
        $this->assertMatchesJsonSnapshot(Json::encode(require $this->classAttributeCache->get()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = Settings::getAppRoot().'/tests/Infrastructure/Attribute/cache';
        @unlink($this->dir.'/AsAmqpQueue.php');
        @rmdir($this->dir);

        $this->classAttributeCache = new ClassAttributeCache(
            AsAmqpQueue::class,
            $this->dir,
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        @unlink($this->dir.'/AsAmqpQueue.php');
        @rmdir($this->dir);
    }
}
