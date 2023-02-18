<?php

namespace App\Domain\Card;

use App\Domain\AI\Prompt;
use App\Domain\Image\FileType;
use App\Domain\Image\Image;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use SleekDB\Store;

class CardRepository
{
    public function __construct(
        private readonly Store $store
    ) {
    }

    public function find(CardId $cardId): Card
    {
        $svg = Settings::getAppRoot().'/public/cards/'.$cardId.'.svg';
        $png = Settings::getAppRoot().'/public/cards/'.$cardId.'.png';

        if (!file_exists($svg) && !file_exists($png)) {
            throw new EntityNotFound(sprintf('Card "%s" not found', $cardId));
        }

        if (!$row = $this->store->findOneBy(['cardId', '==', $cardId])) {
            throw new EntityNotFound(sprintf('Card "%s" not found', $cardId));
        }

        return $this->buildFromResult($row);
    }

    public function findAll(): array
    {
        return array_map(
            fn (array $row) => $this->buildFromResult($row),
            $this->store->findAll(['createdOn' => 'DESC'])
        );
    }

    public function save(
        Card $card,
        Image $image,
      ): void {
        $image->save($card->getCardId());

        $this->store->updateOrInsert([
            'cardId' => $card->getCardId(),
            'cardType' => $card->getCardType()->value,
            'promptForName' => $card->getPromptForPokemonName(),
            'promptForDescription' => $card->getPromptForPokemonDescription(),
            'promptForVisual' => $card->getPromptForVisual(),
            'generatedName' => $card->getGeneratedName(),
            'generatedDescription' => $card->getGeneratedDescription(),
            'fileType' => $card->getFileType()->value,
            'createdOn' => $card->getCreatedOn()->getTimestamp(),
        ]);
    }

    private function buildFromResult(array $result): Card
    {
        return Card::fromState(
            CardId::fromString($result['cardId']),
            CardType::from($result['cardType']),
            Prompt::fromString($result['promptForName']),
            Prompt::fromString($result['promptForDescription']),
            Prompt::fromString($result['promptForVisual']),
            Name::fromString($result['generatedName']),
            Description::fromString($result['generatedDescription']),
            FileType::from($result['fileType']),
            (new \DateTimeImmutable())->setTimestamp($result['createdOn'])
        );
    }
}
