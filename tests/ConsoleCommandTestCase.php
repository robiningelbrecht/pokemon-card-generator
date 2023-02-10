<?php

namespace App\Tests;

use App\Infrastructure\Console\ConsoleCommandContainer;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ConsoleCommandTestCase extends ContainerTestCase
{
    private MockObject $input;
    private MockObject $output;
    private Application $application;

    public function testItShouldBeInConsoleCommandContainer(): void
    {
        /** @var ConsoleCommandContainer $consoleCommandContainer */
        $consoleCommandContainer = $this->getContainer()->get(ConsoleCommandContainer::class);

        $this->assertNotEmpty(array_filter(
            $consoleCommandContainer->getCommands(),
            fn (Command $command) => $command::class === $this->getConsoleCommand()::class
        ), sprintf('ConsoleCommand "%s" not found in ConsoleCommandContainer. Did you tag it with an attribute?', $this->getConsoleCommand()::class));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->application = new Application();
    }

    public function getCommandInApplication(string $name, array $helpers = []): Command
    {
        $this->application->add($this->getConsoleCommand());
        $command = $this->application->find($name);

        foreach ($helpers as $alias => $helper) {
            $command->getHelperSet()->set($helper, $alias);
        }

        return $command;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getOutput()
    {
        return $this->output;
    }

    abstract protected function getConsoleCommand(): Command;
}
