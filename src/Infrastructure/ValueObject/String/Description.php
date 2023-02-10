<?php

namespace App\Infrastructure\ValueObject\String;

class Description extends NonEmptyStringLiteral
{
    public static function fromStringWithMaxChars(string $string, int $maxChars): self
    {
        return self::fromString(strlen($string) > $maxChars ? substr($string, 0, $maxChars - 3).'...' : $string);
    }
}
