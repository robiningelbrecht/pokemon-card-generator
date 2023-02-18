<?php

namespace App\Domain\Image;

use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\Type\PokemonType;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use Twig\Environment;

class SvgGenerator implements ImageGenerator
{
    public function __construct(
        private readonly Environment $twig,
    ) {
    }

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
    ): Image {
        $template = $this->twig->load('card.html.twig');

        return Svg::fromString($template->render([
            'name' => $name,
            'hp' => $hp,
            'visual' => $uriToGeneratedVisual,
            'element' => $type->getElement()->value,
            'weakness' => $type->getWeaknessFor(),
            'resistance' => $type->getResistantFor(),
            'retreatCost' => $rarity->getRetreatCost(),
            'height' => $height,
            'weight' => $weight,
            'description' => $description,
            'moves' => $selectedMoves,
        ]));
    }
}
