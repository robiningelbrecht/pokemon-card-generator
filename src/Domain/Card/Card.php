<?php

namespace App\Domain\Card;

use App\Domain\AI\Prompt;
use App\Domain\Image\FileType;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;

class Card
{
    private function __construct(
        private readonly CardId $cardId,
        private readonly CardType $cardType,
        private readonly Prompt $promptForPokemonName,
        private readonly Prompt $promptForPokemonDescription,
        private readonly Prompt $promptForVisual,
        private readonly Name $generatedName,
        private readonly Description $generatedDescription,
        private readonly FileType $fileType,
        private readonly \DateTimeImmutable $createdOn,
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

    public function getFileType(): FileType
    {
        return $this->fileType;
    }

    public function getUri(): string
    {
        return 'cards/'.$this->cardId.'.'.$this->getFileType()->value;
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
        CardType $cardType,
        Prompt $promptForPokemonName,
        Prompt $promptForPokemonDescription,
        Prompt $promptForVisual,
        Name $generatedName,
        Description $generatedDescription,
        FileType $fileType,
        \DateTimeImmutable $createdOn,
    ): self {
        return new self(
            $cardId,
            $cardType,
            $promptForPokemonName,
            $promptForPokemonDescription,
            $promptForVisual,
            $generatedName,
            $generatedDescription,
            $fileType,
            $createdOn
        );
    }

    public static function fromState(
        CardId $cardId,
        CardType $cardType,
        Prompt $promptForPokemonName,
        Prompt $promptForPokemonDescription,
        Prompt $promptForVisual,
        Name $generatedName,
        Description $generatedDescription,
        FileType $fileType,
        \DateTimeImmutable $createdOn,
    ): self {
        return new self(
            $cardId,
            $cardType,
            $promptForPokemonName,
            $promptForPokemonDescription,
            $promptForVisual,
            $generatedName,
            $generatedDescription,
            $fileType,
            $createdOn
        );
    }
}
