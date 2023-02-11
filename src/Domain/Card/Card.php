<?php

namespace App\Domain\Card;

use App\Domain\AI\Prompt;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;

class Card
{
    private function __construct(
        private readonly CardId $cardId,
        private readonly Prompt $promptForPokemonName,
        private readonly Prompt $promptForPokemonDescription,
        private readonly Prompt $promptForVisual,
        private readonly Name $generatedName,
        private readonly Description $generatedDescription,
        private readonly \DateTimeImmutable $createdOn,
    ) {
    }

    public function getCardId(): CardId
    {
        return $this->cardId;
    }

    public function getUri(): string
    {
        return 'cards/'.$this->cardId.'.svg';
    }

    public function getPromptForPokemonName(): Prompt
    {
        return $this->promptForPokemonName;
    }

    public function getPromptForPokemonDescription(): Prompt
    {
        return $this->promptForPokemonDescription;
    }

    public function getPromptForVisual(): Prompt
    {
        return $this->promptForVisual;
    }

    public function getGeneratedName(): Name
    {
        return $this->generatedName;
    }

    public function getGeneratedDescription(): Description
    {
        return $this->generatedDescription;
    }

    public function getCreatedOn(): \DateTimeImmutable
    {
        return $this->createdOn;
    }

    public static function create(
        CardId $cardId,
        Prompt $promptForPokemonName,
        Prompt $promptForPokemonDescription,
        Prompt $promptForVisual,
        Name $generatedName,
        Description $generatedDescription,
        \DateTimeImmutable $createdOn,
    ): self {
        return new self(
            $cardId,
            $promptForPokemonName,
            $promptForPokemonDescription,
            $promptForVisual,
            $generatedName,
            $generatedDescription,
            $createdOn
        );
    }

    public static function fromState(
        CardId $cardId,
        Prompt $promptForPokemonName,
        Prompt $promptForPokemonDescription,
        Prompt $promptForVisual,
        Name $generatedName,
        Description $generatedDescription,
        \DateTimeImmutable $createdOn,
    ): self {
        return new self(
            $cardId,
            $promptForPokemonName,
            $promptForPokemonDescription,
            $promptForVisual,
            $generatedName,
            $generatedDescription,
            $createdOn
        );
    }
}
