<?php

namespace App\Domain\Card;

use App\Domain\AI\Prompt;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use App\Infrastructure\ValueObject\String\Svg;
use Spatie\Valuestore\Valuestore;
use Symfony\Component\Finder\Finder;

class CardRepository
{
    public function __construct(
        private readonly Valuestore $valueStore,
    ) {
    }

    public function find(CardId $cardId): array
    {
        $file = Settings::getAppRoot().'/public/cards/'.$cardId.'.svg';

        if (!file_exists($file)) {
            throw new EntityNotFound(sprintf('Card "%s" not found', $cardId));
        }

        $metadata = $this->valueStore->get((string) $cardId);

        return [
            'cardId' => $cardId,
            'uri' => 'cards/'.$cardId.'.svg',
            'metadata' => $metadata,
        ];
    }

    public function findAll(): array
    {
        $finder = new Finder();
        $finder->files()->in(Settings::getAppRoot().'/public/cards')->name('*.svg')->sortByModifiedTime();

        $cards = [];
        foreach ($finder as $file) {
            /* @var \Symfony\Component\Finder\SplFileInfo $file */
            $cardId = CardId::fromString(str_replace('.svg', '', $file->getFilename()));
            $cards[] = $this->find($cardId);
        }

        return array_reverse($cards);
    }

    public function save(
        CardId $cardId,
        Svg $svg,
        Prompt $promptForPokemonName,
        Prompt $promptForPokemonDescription,
        Prompt $promptForVisual,
        Name $generatedName,
        Description $generatedDescription): void
    {
        $file = Settings::getAppRoot().'/public/cards/'.$cardId.'.svg';
        file_put_contents($file, $svg);

        // Store some metadata, so we can display it in the CLI and UI.
        $this->valueStore->put([(string) $cardId => [
            'promptForName' => $promptForPokemonName,
            'promptForDescription' => $promptForPokemonDescription,
            'promptForVisual' => $promptForVisual,
            'generatedName' => $generatedName,
            'generatedDescription' => $generatedDescription,
        ]]);
    }
}
