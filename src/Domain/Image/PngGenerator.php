<?php

namespace App\Domain\Image;

use App\Domain\Pokemon\Move\PokemonMove;
use App\Domain\Pokemon\PokemonElement;
use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\Type\PokemonType;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use Intervention\Image\Gd\Font;
use Intervention\Image\ImageManager;

class PngGenerator implements ImageGenerator
{
    public function __construct(
        private readonly ImageManager $imageManager
    ) {
    }

    private static function fontRegular(): string
    {
        return Settings::getAppRoot().'/public/assets/font/cabin-v26-latin-regular.woff';
    }

    private static function fontBold(): string
    {
        return Settings::getAppRoot().'/public/assets/font/cabin-v26-latin-700.woff';
    }

    private static function fontColor(PokemonElement $element): string
    {
        return PokemonElement::DARK === $element ? '#FFFFFF' : '#1D160E';
    }

    public function make(
        Name $name,
        PokemonType $type,
        PokemonRarity $rarity,
        Description $pokemonDescription,
        string $uriToGeneratedVisual,
        int $hp,
        array $selectedMoves,
        string $height,
        string $weight
    ): Image {
        $fontColor = self::fontColor($type->getElement());

        /** @var \Intervention\Image\Image $image */
        $png = $this->imageManager->make(sprintf(Settings::getAppRoot().'/public/assets/cards/%s.png', $type->getElement()->value));

        // Name.
        $canvas = $this->imageManager->canvas(240, 31);
        $canvas->text($name, 0, 24, function (Font $font) use ($fontColor) {
            $font->file(self::fontBold());
            $font->size(26);
            $font->color($fontColor);
        });
        $png->insert($canvas, 'top-left', 49, 42);

        // HP.
        $canvas = $this->imageManager->canvas(90, 26);
        $canvas->text($hp.' HP', $canvas->getWidth(), 24, function (Font $font) {
            $font->file(self::fontBold());
            $font->size(26);
            $font->color('#FC0D05');
            $font->align('right');
        });
        $png->insert($canvas, 'top-right', 90, 42);

        // Visual.
        $generatedPokemonImage = $this->imageManager->make(file_get_contents($uriToGeneratedVisual))->fit(359, 254);
        $png->insert($generatedPokemonImage, 'top-left', 58, 84);

        // Dimensions.
        $canvas = $this->imageManager->canvas(338, 18);
        $canvas->text(sprintf('%s Pokémon. Height: %s, Weight: %slbs.', ucfirst($type->getElement()->value), $height, $weight), $canvas->getWidth() / 2, 16, function (Font $font) {
            $font->file(self::fontBold());
            $font->size(12);
            $font->color('#1D160E');
            $font->align('center');
        });
        $png->insert($canvas, 'top-left', 69, 355);

        // Moves.
        $movesCanvas = $this->imageManager->canvas(378, 145);
        $firstMoveCanvas = $this->buildMoveCanvas(reset($selectedMoves), $fontColor);
        $position = match (count($selectedMoves)) {
            1 => 'center',
            2 => 'top-left',
        };
        $movesCanvas->insert($firstMoveCanvas, $position);
        if (2 === count($selectedMoves)) {
            $line = $this->imageManager->canvas(378, 2, $fontColor);
            $movesCanvas->insert($line, 'center');

            $secondMoveCanvas = $this->buildMoveCanvas(end($selectedMoves), $fontColor);
            $movesCanvas->insert($secondMoveCanvas, 'bottom-left');
        }

        $png->insert($movesCanvas, 'top-left', 49, 380);

        // Weakness.
        $png->insert($this->buildElementsCanvas(...$type->getWeaknessFor()), 'top-left', 47, 558);

        // Resistance.
        $png->insert($this->buildElementsCanvas(...$type->getResistantFor()), 'top-left', 207, 558);

        // Retreat cost.
        $png->insert($this->buildElementsCanvas(...array_fill(0, $rarity->getRetreatCost(), PokemonElement::NORMAL)), 'top-left', 360, 558);

        // Description.
        $canvas = $this->imageManager->canvas(382, 30);
        $lines = explode("\n", wordwrap($pokemonDescription, 70));
        foreach ($lines as $delta => $line) {
            $yOffset = (($delta + 1) * 14);
            $canvas->text($line, 382 / 2, $yOffset, function (Font $font) use ($fontColor) {
                $font->file(self::fontBold());
                $font->size(12);
                $font->color($fontColor);
                $font->align('center');
            });
        }
        $png->insert($canvas, 'top-left', 46, 590);

        return Png::fromInterventionImage($png);
    }

    private function buildMoveCanvas(PokemonMove $move, string $fontColor): \Intervention\Image\Image
    {
        $moveCanvas = $this->imageManager->canvas(378, 60);
        $elementsCanvas = $this->imageManager->canvas(60, 60);
        foreach ($move->getCost() as $elementDelta => $element) {
            $element = $this->imageManager->make(
                file_get_contents(sprintf(Settings::getAppRoot().'/public/assets/elements/%s.png', $element->value))
            )->resize(30, 30);

            $position = match (count($move->getCost())) {
                1 => 'center',
                2 => 0 === $elementDelta ? 'left' : 'right',
                3 => $elementDelta < 2 ? ('top-'.(0 === $elementDelta % 2 ? 'left' : 'right')) : 'bottom',
                4 => ($elementDelta < 2 ? 'top' : 'bottom').'-'.(0 === $elementDelta % 2 ? 'left' : 'right')
            };
            $elementsCanvas->insert($element, $position);
        }
        $moveCanvas->insert($elementsCanvas, 'left');

        $damageCanvas = $this->imageManager->canvas(46, 30);
        $damageCanvas->text($move->getPower(), 23, 24, function (Font $font) use ($fontColor) {
            $font->file(self::fontRegular());
            $font->size(24);
            $font->color($fontColor);
            $font->align('center');
        });
        $moveCanvas->insert($damageCanvas, 'right');

        $moveNameCanvas = $this->imageManager->canvas(260, 50);
        $valign = $move->getDescription() ? 'top' : 'middle';
        $y = $move->getDescription() ? 10 : 25;
        $moveNameCanvas->text($move->getLabel(), 130, $y, function (Font $font) use ($fontColor, $valign) {
            $font->file(self::fontBold());
            $font->size(16);
            $font->color($fontColor);
            $font->align('center');
            $font->valign($valign);
        });

        if ($move->getDescription()) {
            $descriptionMaxLength = 48;
            $description = trim(preg_replace('/\s+/', ' ', $move->getDescription()));
            $description = strlen($description) > $descriptionMaxLength ? trim(substr($description, 0, $descriptionMaxLength - 3)).'...' : $description;

            $moveNameCanvas->text($description, 133, 38, function (Font $font) use ($fontColor) {
                $font->file(self::fontRegular());
                $font->size(12);
                $font->color($fontColor);
                $font->align('center');
            });
        }
        $moveCanvas->insert($moveNameCanvas, 'left', 70);

        return $moveCanvas;
    }

    private function buildElementsCanvas(PokemonElement ...$pokemonElements): \Intervention\Image\Image
    {
        $canvas = $this->imageManager->canvas(60, 20);
        $elements = array_slice($pokemonElements, 0, 3);

        foreach ($elements as $delta => $element) {
            $elementImage = $this->imageManager->make(
                file_get_contents(sprintf(Settings::getAppRoot().'/public/assets/elements/%s.png', $element->value))
            )->resize(20, 20);

            $position = match (count($elements)) {
                1 => 'center',
                2 => 'left',
                3 => match ($delta) {
                    0 => 'left',
                    1 => 'center',
                    2 => 'right',
                }
            };
            $x = match (count($elements)) {
                1, 3 => 0,
                2 => match ($delta) {
                    0 => '10',
                    1 => '30',
                }
            };
            $canvas->insert($elementImage, $position, $x);
        }

        return $canvas;
    }
}
