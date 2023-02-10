<?php

namespace App\Domain\Pokemon;

enum PokemonSize: string
{
    case XL = 'xl';
    case L = 'l';
    case M = 'm';
    case S = 's';
    case XS = 'xs';

    public function getInteger(): int
    {
        return match ($this) {
            self::XS => 0,
            self::S => 1,
            self::M => 2,
            self::L => 3,
            self::XL => 4,
        };
    }

    public function getAdjectives(): array
    {
        return match ($this) {
            self::XS, self::S => ['chibi cute', 'chibi young'],
            self::M => ['young', 'dynamic'],
            self::L, self::XL => ['gigantic', 'massive'],
        };
    }
}
