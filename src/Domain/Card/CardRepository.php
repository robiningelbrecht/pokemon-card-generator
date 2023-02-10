<?php

namespace App\Domain\Card;

use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\ValueObject\String\Svg;
use Symfony\Component\Finder\Finder;

class CardRepository
{
    public function find(CardId $cardId): string
    {
        $file = Settings::getAppRoot().'/public/cards/'.$cardId.'.svg';

        if (!file_exists($file)) {
            throw new EntityNotFound(sprintf('Card "%s" not found', $cardId));
        }

        return 'cards/'.$cardId.'.svg';
    }

    public function findAll(): array
    {
        $finder = new Finder();
        $finder->files()->in(Settings::getAppRoot().'/public/cards')->name('*.svg')->sortByModifiedTime();

        $cards = [];
        foreach ($finder as $file) {
            /* @var \Symfony\Component\Finder\SplFileInfo $file */
            $cards[] = 'cards/'.$file->getFilename();
        }

        return array_reverse($cards);
    }

    public function save(CardId $cardId, Svg $svg): void
    {
        $file = Settings::getAppRoot().'/public/cards/'.$cardId.'.svg';
        file_put_contents($file, $svg);
    }
}
