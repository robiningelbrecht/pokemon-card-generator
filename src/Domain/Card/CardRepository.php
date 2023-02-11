<?php

namespace App\Domain\Card;

use App\Domain\AI\Prompt;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use App\Infrastructure\ValueObject\String\Svg;
use SleekDB\Store;

class CardRepository
{
    public function __construct(
        private readonly Store $store
    ) {
    }

    public function find(CardId $cardId): Card
    {
        $file = Settings::getAppRoot().'/public/cards/'.$cardId.'.svg';

        if (!file_exists($file)) {
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
            $this->store->findAll(['createdOn' => 'ASC'])
        );
    }

    public function save(
        Card $card,
        Svg $svg,
      ): void {
        $file = Settings::getAppRoot().'/public/cards/'.$card->getCardId().'.svg';
        file_put_contents($file, $svg);

        $this->store->updateOrInsert([
            'cardId' => $card->getCardId(),
            'promptForName' => $card->getPromptForPokemonName(),
            'promptForDescription' => $card->getPromptForPokemonDescription(),
            'promptForVisual' => $card->getPromptForVisual(),
            'generatedName' => $card->getGeneratedName(),
            'generatedDescription' => $card->getGeneratedDescription(),
            'createdOn' => $card->getCreatedOn()->getTimestamp(),
        ]);
    }

    private function buildFromResult(array $result): Card
    {
        return Card::fromState(
            CardId::fromString($result['cardId']),
            Prompt::fromString($result['promptForName']),
            Prompt::fromString($result['promptForDescription']),
            Prompt::fromString($result['promptForVisual']),
            Name::fromString($result['generatedName']),
            Description::fromString($result['generatedDescription']),
            (new \DateTimeImmutable())->setTimestamp($result['createdOn'])
        );
    }
}
