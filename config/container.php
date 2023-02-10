<?php

use App\Domain\AI\ReplicateApiKey;
use App\Domain\Pokemon\Move\PokemonMoveRepository;
use App\Domain\Pokemon\Type\PokemonTypeRepository;
use App\Infrastructure\AMQP\AMQPStreamConnectionFactory;
use App\Infrastructure\Console\ConsoleCommandContainer;
use App\Infrastructure\Environment\Environment;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Twig\TwigBuilder;
use Dotenv\Dotenv;
use Lcobucci\Clock\Clock;
use Lcobucci\Clock\SystemClock;
use OpenAI\Client;
use SleekDB\Store;
use Spatie\Valuestore\Valuestore;
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
    ReplicateApiKey::class => fn () => ReplicateApiKey::fromString($_ENV['REPLICATE_API_KEY']),
    Client::class => fn () => OpenAI::client($_ENV['OPEN_AI_API_KEY']),
    Valuestore::class => fn () => Valuestore::make($appRoot.'/'.$_ENV['VALUE_STORE_PATH']),
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
    // AMQP.
    AMQPStreamConnectionFactory::class => function (Settings $settings) {
        $rabbitMqConfig = $settings->get('amqp.rabbitmq');

        return new AMQPStreamConnectionFactory(
            $rabbitMqConfig['host'],
            $rabbitMqConfig['port'],
            $rabbitMqConfig['username'],
            $rabbitMqConfig['password'],
            $rabbitMqConfig['vhost']
        );
    },
];
