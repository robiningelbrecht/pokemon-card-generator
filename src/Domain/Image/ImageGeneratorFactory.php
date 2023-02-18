<?php

namespace App\Domain\Image;

class ImageGeneratorFactory
{
    private array $generators = [];

    public function subscribeImageGenerator(FileType $fileType, ImageGenerator $imageGenerator): void
    {
        $this->generators[$fileType->value] = $imageGenerator;
    }

    public function getForFileType(FileType $fileType): ImageGenerator
    {
        return $this->generators[$fileType->value] ??
            throw new \RuntimeException(sprintf('No ImageGenerator subscribed for file type "%s"', $fileType->value));
    }
}
