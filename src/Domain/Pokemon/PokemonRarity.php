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
            self::UNCOMMON => 1,
            self::COMMON => 2,
            self::RARE => 3,
        };
    }

    public function getBonus(): int
    {
        return match ($this) {
            self::UNCOMMON => 1,
            self::COMMON => 2,
            self::RARE => 3,
        };
    }

    public function getMinAllowedPpForMove(): int
    {
        return match ($this) {
            self::UNCOMMON => 25,
            self::COMMON => 15,
            self::RARE => -1,
        };
    }

    public function getAdjectives(): array
    {
        return match ($this) {
            self::UNCOMMON => ['simple', 'basic'],
            self::COMMON => ['strong', 'rare', 'special'],
            self::RARE => ['legendary', 'epic', 'mythical'],
        };
    }
}
