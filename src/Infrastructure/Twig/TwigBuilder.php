<?php

namespace App\Infrastructure\Twig;

use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigBuilder
{
    public function __construct(
        private readonly FilesystemLoader $filesystemLoader
    ) {
    }

    public function build(): TwigEnvironment
    {
        $twig = new TwigEnvironment($this->filesystemLoader);
        $twig->addFunction(new TwigFunction('image64', [Base64TwigExtension::class, 'image']));
        $twig->addFunction(new TwigFunction('font64', [Base64TwigExtension::class, 'font']));

        return $twig;
    }
}
