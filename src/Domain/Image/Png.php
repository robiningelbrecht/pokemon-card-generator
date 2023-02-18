<?php

namespace App\Domain\Image;

use App\Infrastructure\Environment\Settings;
use Intervention\Image\Image as InterventionImage;

class Png implements Image
{
    private function __construct(
        private readonly InterventionImage $interventionImage,
    ) {
    }

    public static function fromInterventionImage(InterventionImage $image): self
    {
        return new self($image);
    }

    public function save(string $fileName): void
    {
        $this->interventionImage->save(Settings::getAppRoot().'/public/cards/'.$fileName.'.png', 100);
    }
}
