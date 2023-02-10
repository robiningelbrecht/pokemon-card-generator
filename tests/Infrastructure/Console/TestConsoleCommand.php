<?php

namespace App\Tests\Infrastructure\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'test:command', description: 'Test command')]
class TestConsoleCommand extends Command
{
}
