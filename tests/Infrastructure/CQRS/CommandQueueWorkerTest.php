<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Queue\FailedQueue\FailedQueue;
use App\Infrastructure\AMQP\Queue\FailedQueue\FailedQueueFactory;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\CQRS\CommandQueueWorker;
use App\Tests\PausedClock;
use Lcobucci\Clock\Clock;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandQueueWorkerTest extends TestCase
{
    private CommandQueueWorker $commandQueueWorker;
    private MockObject $commandBus;
    private MockObject $failedQueueFactory;
    private Clock $clock;

    public function testProcessMessage(): void
    {
        $message = $this->createMock(AMQPMessage::class);
        $command = new TestCommand();

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($command);

        $this->commandQueueWorker->processMessage(
            $command,
            $message
        );
        $this->assertEquals('command-queue-worker', $this->commandQueueWorker->getName());
    }

    public function testProcessFailure(): void
    {
        $message = $this->createMock(AMQPMessage::class);
        $command = new TestCommand();
        $queue = $this->createMock(Queue::class);
        $failedQueue = $this->createMock(FailedQueue::class);

        $this->failedQueueFactory
            ->expects($this->once())
            ->method('buildFor')
            ->with($queue)
            ->willReturn($failedQueue);

        $failedQueue
            ->expects($this->once())
            ->method('queue')
            ->with($command);

        $this->commandQueueWorker->processFailure(
            $command,
            $message,
            new \RuntimeException('A grave error'),
            $queue
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->createMock(CommandBus::class);
        $this->failedQueueFactory = $this->createMock(FailedQueueFactory::class);
        $this->clock = PausedClock::on(new \DateTimeImmutable('2022-07-01'));

        $this->commandQueueWorker = new CommandQueueWorker(
            $this->commandBus,
            $this->failedQueueFactory,
            $this->clock
        );
    }
}
