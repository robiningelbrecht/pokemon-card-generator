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

    public function getBonus(): int
    {
        return match ($this) {
            self::COMMON => 1,
            self::UNCOMMON => 2,
            self::RARE => 3,
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
