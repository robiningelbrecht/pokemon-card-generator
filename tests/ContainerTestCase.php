<?php

namespace App\Tests;

use App\Infrastructure\DependencyInjection\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class ContainerTestCase extends TestCase
{
    private static ?ContainerInterface $container = null;

    protected function setUp(): void
    {
        parent::setUp();
        self::$container = $this->bootContainer();
    }

    public function bootContainer(): ContainerInterface
    {
        if (!self::$container) {
            self::$container = ContainerFactory::createForTestSuite();
        }

        return self::$container;
    }

    public function getContainer(): ContainerInterface
    {
        return self::$container;
    }
}
