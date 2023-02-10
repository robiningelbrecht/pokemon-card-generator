<?php

namespace App\Infrastructure\Environment;

class Settings
{
    public function __construct(
        /** @var array<mixed> $settings */
        private readonly array $settings
    ) {
    }

    public function get(string $parents): mixed
    {
        $settings = $this->settings;
        $parents = explode('.', $parents);

        foreach ($parents as $parent) {
            if (is_array($settings) && (isset($settings[$parent]) || array_key_exists($parent, $settings))) {
                $settings = $settings[$parent];
            } else {
                throw new \RuntimeException(sprintf('Trying to fetch invalid setting "%s"', implode('.', $parents)));
            }
        }

        return $settings;
    }

    public static function load(): self
    {
        return new self(require self::getAppRoot().'/config/settings.php');
    }

    public static function getAppRoot(): string
    {
        return dirname(__DIR__, 3);
    }
}
