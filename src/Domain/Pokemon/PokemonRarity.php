<?php

namespace App\Domain\Pokemon;

enum PokemonRarity: string
{
    case COMMON = 'common';
    case UNCOMMON = 'uncommon';
    case RARE = 'rare';

    public function getRetreatCost(): int
    {
        return match ($this) {
            self::COMMON => 1,
            self::UNCOMMON => 2,
            self::RARE => 3,
        };
    }

    public function getLevelRange(): array
    {
        return match ($this) {
            self::COMMON => [30, 50],
            self::UNCOMMON => [60, 90],
            self::RARE => [80, 120],
        };
    }

    public function getMinAllowedPpForMove(): int
    {
        return match ($this) {
            self::COMMON => 25,
            self::UNCOMMON => 15,
            self::RARE => -1,
        };
    }

    public function getAdjectives(): array
    {
        return match ($this) {
            self::COMMON => ['simple', 'basic'],
            self::UNCOMMON => ['strong', 'rare', 'special'],
            self::RARE => ['legendary', 'epic', 'mythical'],
        };
    }
}
