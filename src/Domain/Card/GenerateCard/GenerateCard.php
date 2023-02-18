<?php

namespace App\Domain\Card\GenerateCard;

use App\Domain\Card\CardId;
use App\Domain\Card\CardType;
use App\Domain\Card\Creature\Creature;
use App\Domain\Image\FileType;
use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\PokemonSize;
use App\Infrastructure\CQRS\DomainCommand;

class GenerateCard extends DomainCommand
{
    public function __construct(
        private readonly CardId $cardId,
        private readonly CardType $cardType,
        private readonly PokemonRarity $pokemonRarity,
        private readonly PokemonSize $pokemonSize,
        private readonly Creature $creature,
        private readonly FileType $fileType,
    ) {
    }

    public function getCardId(): CardId
    {
        return $this->cardId;
    }

    public function getCardType(): CardType
    {
        return $this->cardType;
    }

    public function getPokemonRarity(): PokemonRarity
    {
        return $this->pokemonRarity;
    }

    public function getPokemonSize(): PokemonSize
    {
        return $this->pokemonSize;
    }

    public function getCreature(): Creature
    {
        return $this->creature;
    }

    public function getFileType(): FileType
    {
        return $this->fileType;
    }
}
