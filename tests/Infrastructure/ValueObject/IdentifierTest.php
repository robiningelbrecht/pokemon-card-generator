<?php

namespace App\Tests\Infrastructure\ValueObject;

use PHPUnit\Framework\TestCase;

class IdentifierTest extends TestCase
{
    public function testItShouldCastToAndFromString(): void
    {
        $testIdentifier = TestIdentifier::random();
        static::assertEquals(TestIdentifier::fromString($testIdentifier), (string) $testIdentifier);

        $testIdentifier = TestEmptyPrefixIdentifier::random();
        static::assertEquals(TestEmptyPrefixIdentifier::fromString($testIdentifier), (string) $testIdentifier);
    }

    public function testItShouldThrowWhenEmptyIdentifier(): void
    {
        $this->expectExceptionMessage('App\Tests\Infrastructure\ValueObject\TestIdentifier cannot be empty');
        $this->expectException(\InvalidArgumentException::class);
        TestIdentifier::fromString('');
    }

    public function testItShouldThrowWhenInvalidPrefix(): void
    {
        $this->expectExceptionMessage('Identifier does not start with prefix "test-", got: invalid-test');
        $this->expectException(\InvalidArgumentException::class);
        TestIdentifier::fromString('invalid-test');
    }
}
