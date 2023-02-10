<?php

namespace App\Tests\Console;

use App\Console\AmqpConsumeConsoleCommand;
use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Consumer;
use App\Infrastructure\AMQP\Queue\QueueContainer;
use App\Tests\ConsoleCommandTestCase;
use App\Tests\Infrastructure\AMQP\Queue\TestQueue;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class AmqpConsumeConsoleCommandTest extends ConsoleCommandTestCase
{
    private AmqpConsumeConsoleCommand $amqpConsumeConsoleCommand;
    private MockObject $queueContainer;
    private MockObject $consumer;

    public function testExecute(): void
    {
        $queue = new TestQueue($this->createMock(AMQPChannelFactory::class));

        $this->queueContainer
            ->expects($this->once())
            ->method('getQueue')
            ->with('test-queue')
            ->willReturn($queue);

        $this->consumer
            ->expects($this->once())
            ->method('consume')
            ->with($queue);

        $command = $this->getCommandInApplication('amqp:consume');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'queue' => 'test-queue',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->queueContainer = $this->createMock(QueueContainer::class);
        $this->consumer = $this->createMock(Consumer::class);

        $this->amqpConsumeConsoleCommand = new AmqpConsumeConsoleCommand(
            $this->queueContainer,
            $this->consumer,
        );
    }

    protected function getConsoleCommand(): Command
    {
        return $this->amqpConsumeConsoleCommand;
    }
}
