<?php

namespace App\Tests\Infrastructure\Console;

use App\Infrastructure\Console\ConsoleCommandContainer;
use App\Infrastructure\Serialization\Json;
use App\Tests\ContainerTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ConsoleCommandContainerTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private ConsoleCommandContainer $consoleCommandContainer;

    public function testItRegistersAllCommand(): void
    {
        $commands = array_keys($this->consoleCommandContainer->getCommands());
        sort($commands);
        $this->assertMatchesJsonSnapshot(Json::encode($commands));
    }

    public function testItShouldThrowWhenEmptyName(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Command name cannot be empty');

        $this->consoleCommandContainer->registerCommand(new TestConsoleCommandNoName());
    }

    public function testItShouldThrowWhenDuplicateCommand(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Command "test:command" already registered in container');

        $this->consoleCommandContainer->registerCommand(new TestConsoleCommand());
        $this->consoleCommandContainer->registerCommand(new TestConsoleCommand());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->consoleCommandContainer = $this->getContainer()->get(ConsoleCommandContainer::class);
    }
}
