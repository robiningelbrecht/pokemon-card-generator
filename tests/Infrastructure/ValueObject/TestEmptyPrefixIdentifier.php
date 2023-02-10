<?php

namespace App\Tests\Infrastructure\ValueObject;

use App\Infrastructure\ValueObject\Identifier;

class TestEmptyPrefixIdentifier extends Identifier
{
    public static function getPrefix(): string
    {
        return '';
    }
}
