<?php

namespace App\Domain\Image;

interface Image
{
    public function save(string $fileName): void;
}
