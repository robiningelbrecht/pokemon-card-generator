<?php

namespace App\Domain\Card;

use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\PokemonSize;

class CardRandomizer
{
    public function randomizeHpByRarity(PokemonRarity $pokemonRarity): int
    {
        [$min, $max] = $pokemonRarity->getLevelRange();
        $range = range($min, $max, 10);

        return $range[array_rand($range)];
    }

    public function randomizeHeightBySize(PokemonSize $size): string
    {
        // min height = 0′04″	(0.1m)
        // max height = 65′07″	(20.0m)
        $maxHeight = 6500;
        $minHeight = 4;

        $delta = (int) floor(($maxHeight - $minHeight) / count(PokemonSize::cases()));

        return (string) (mt_rand($size->getInteger() * $delta, ($size->getInteger() + 1) * $delta) / 100);
    }

    public function randomizeWeightBySize(PokemonSize $size): string
    {
        // min weight = 0.2lbs  (0.1kg)
        // max weight = 2204.4lbs  (999.9kg)
        $maxWeight = 220440;
        $minWeight = 20;

        $delta = (int) floor(($maxWeight - $minWeight) / count(PokemonSize::cases()));

        return (string) (mt_rand($size->getInteger() * $delta, ($size->getInteger() + 1) * $delta) / 100);
    }
}
