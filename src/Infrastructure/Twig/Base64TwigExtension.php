<?php

namespace App\Infrastructure\Twig;

use App\Infrastructure\Environment\Settings;

class Base64TwigExtension
{
    public static function image(string $asset): string
    {
        $extension = pathinfo($asset, PATHINFO_EXTENSION);
        if (!filter_var($asset, FILTER_VALIDATE_URL)) {
            $binary = file_get_contents(Settings::getAppRoot().'/public/'.$asset);
        } else {
            $binary = file_get_contents($asset);
        }

        return sprintf('data:image/%s;base64,%s', $extension, base64_encode($binary));
    }

    public static function font(string $name): string
    {
        $binary = file_get_contents(Settings::getAppRoot().'/public/assets/font/'.$name);

        return sprintf('data:application/font-woff;charset=utf-8;base64,%s', base64_encode($binary));
    }
}
