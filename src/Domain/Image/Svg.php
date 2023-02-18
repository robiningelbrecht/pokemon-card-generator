<?php

namespace App\Domain\Image;

use App\Infrastructure\Environment\Settings;

class Svg implements Image
{
    private function __construct(
        private readonly string $string,
    ) {
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public function save(string $fileName): void
    {
        $file = Settings::getAppRoot().'/public/cards/'.$fileName.'.svg';
        file_put_contents($file, $this->string);
    }
}
