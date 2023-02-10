<?php

namespace App\Tests\Infrastructure\ValueObject\String;

use PHPUnit\Framework\TestCase;

class NonEmptyStringLiteralTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals('test', (string) TestNonEmptyStringLiteral::fromString('test'));
        $this->assertEquals('test', (string) TestNonEmptyStringLiteral::fromOptionalString('test'));
        $this->assertNull(TestNonEmptyStringLiteral::fromOptionalString());
        $this->assertNull(TestNonEmptyStringLiteral::fromOptionalString(''));
    }

    public function testJsonSerialize(): void
    {
        $this->assertEquals('"test"', json_encode(TestNonEmptyStringLiteral::fromOptionalString('test')));
    }

    public function testItShouldThrowWhenEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('App\Tests\Infrastructure\ValueObject\String\TestNonEmptyStringLiteral can not be empty');

        TestNonEmptyStringLiteral::fromString('');
    }
}
