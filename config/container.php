<?php

use App\Domain\AI\ReplicateApiKey;
use App\Domain\Card\CardRepository;
use App\Domain\Pokemon\Move\PokemonMoveRepository;
use App\Domain\Pokemon\Type\PokemonTypeRepository;
use App\Infrastructure\Console\ConsoleCommandContainer;
use App\Infrastructure\Environment\Environment;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Twig\TwigBuilder;
use Dotenv\Dotenv;
use Lcobucci\Clock\Clock;
use Lcobucci\Clock\SystemClock;
use OpenAI\Client;
use SleekDB\Store;
use Symfony\Component\Console\Application;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

$appRoot = Settings::getAppRoot();

$dotenv = Dotenv::createImmutable($appRoot);
$dotenv->load();

return [
    PokemonMoveRepository::class => DI\autowire()->constructorParameter('store', new Store('moves', $appRoot.'/database', [
        'auto_cache' => false,
        'primary_key' => 'id',
        'timeout' => false,
    ])),
    PokemonTypeRepository::class => DI\autowire()->constructorParameter('store', new Store('types', $appRoot.'/database', [
        'auto_cache' => false,
        'primary_key' => 'id',
        'timeout' => false,
    ])),
    CardRepository::class => DI\autowire()->constructorParameter('store', new Store('cards', $appRoot.'/database', [
        'auto_cache' => false,
        'timeout' => false,
    ])),
    \App\Domain\Image\ImageGeneratorFactory::class => DI\autowire()
        ->method('subscribeImageGenerator', \App\Domain\Image\FileType::SVG, DI\get(\App\Domain\Image\SvgGenerator::class))
        ->method('subscribeImageGenerator', \App\Domain\Image\FileType::PNG, DI\get(\App\Domain\Image\PngGenerator::class)),
    ReplicateApiKey::class => fn () => ReplicateApiKey::fromString($_ENV['REPLICATE_API_KEY']),
    Client::class => fn () => OpenAI::client($_ENV['OPEN_AI_API_KEY']),
    // Clock.
    Clock::class => DI\factory([SystemClock::class, 'fromSystemTimezone']),
    // Twig Environment.
    FilesystemLoader::class => DI\create(FilesystemLoader::class)->constructor($appRoot.'/templates'),
    TwigEnvironment::class => DI\factory([TwigBuilder::class, 'build']),
    // Console command application.
    Application::class => function (ConsoleCommandContainer $consoleCommandContainer) {
        $application = new Application();
        foreach ($consoleCommandContainer->getCommands() as $command) {
            $application->add($command);
        }

        return $application;
    },
    // Environment.
    Environment::class => fn () => Environment::from($_ENV['ENVIRONMENT']),
    // Settings.
    Settings::class => DI\factory([Settings::class, 'load']),
];
