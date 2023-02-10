<?php

namespace App\Domain\Card;

use App\Infrastructure\ValueObject\Identifier;

class CardId extends Identifier
{
    public static function getPrefix(): string
    {
        return 'card-';
    }
}
