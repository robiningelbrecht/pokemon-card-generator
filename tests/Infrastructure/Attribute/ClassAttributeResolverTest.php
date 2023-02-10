<?php

namespace App\Tests\Infrastructure\Attribute;

use App\Infrastructure\Attribute\ClassAttributeResolver;
use App\Infrastructure\Environment\Settings;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Console\Attribute\AsCommand;

class ClassAttributeResolverTest extends TestCase
{
    use MatchesSnapshots;

    private string $dir;

    public function testResolve(): void
    {
        $resolver = new ClassAttributeResolver();
        $classes = $resolver->resolve(AsCommand::class, ['src/Console']);
        sort($classes);

        $this->assertMatchesJsonSnapshot($classes);
    }

    public function testResolveWithCache(): void
    {
        $resolver = new ClassAttributeResolver();
        $classes = $resolver->resolve(
            AsCommand::class,
            ['src/Console'],
            $this->dir
        );
        $this->assertEquals($classes, $resolver->resolve(
            AsCommand::class,
            ['src/Console'],
            $this->dir
        ));
        sort($classes);

        $this->assertFileExists($this->dir.'/AsCommand.php');
        $this->assertMatchesJsonSnapshot($classes);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = Settings::getAppRoot().'/tests/Infrastructure/Attribute/cache';
        @unlink($this->dir.'/AsCommand.php');
        @rmdir($this->dir);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        @unlink($this->dir.'/AsCommand.php');
        @rmdir($this->dir);
    }
}
