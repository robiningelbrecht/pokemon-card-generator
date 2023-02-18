<?php

namespace App\Domain\Image;

use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\Type\PokemonType;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;

interface ImageGenerator
{
    public function make(
        Name $name,
        PokemonType $type,
        PokemonRarity $rarity,
        Description $description,
        string $uriToGeneratedVisual,
        int $hp,
        array $selectedMoves,
        string $height,
        string $weight,
    ): Image;
}
