<?php

namespace App\Tests\Infrastructure\CQRS;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Envelope;
use App\Infrastructure\CQRS\CommandQueueWorker;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandQueueTest extends TestCase
{
    private TestCommandQueue $commandQueue;
    private MockObject $AMQPChannelFactory;
    private MockObject $commandQueueWorker;

    public function testGetWorker(): void
    {
        $this->assertInstanceOf(CommandQueueWorker::class, $this->commandQueue->getWorker());
    }

    public function testQueue(): void
    {
        $command = new TestCommand();
        $amqpChannel = $this->createMock(AMQPChannel::class);

        $this->AMQPChannelFactory
            ->expects($this->once())
            ->method('getForQueue')
            ->with($this->commandQueue)
            ->willReturn($amqpChannel);

        $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
        $message = new AMQPMessage(serialize($command), $properties);

        $amqpChannel
            ->expects($this->once())
            ->method('basic_publish')
            ->with($message, '', 'test-command-queue');

        $this->commandQueue->queue($command);
    }

    public function testQueueItShouldThrowWhenInvalidEnvelope(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Queue "test-command-queue" requires a command to be queued, Envelope given');

        $this->commandQueue->queue($this->getMockBuilder(Envelope::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMockClassName('Envelope')
            ->getMock());
    }

    public function testQueueBatch(): void
    {
        $command = new TestCommand();
        $amqpChannel = $this->createMock(AMQPChannel::class);

        $this->AMQPChannelFactory
            ->expects($this->once())
            ->method('getForQueue')
            ->with($this->commandQueue)
            ->willReturn($amqpChannel);

        $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
        $message = new AMQPMessage(serialize($command), $properties);

        $amqpChannel
            ->expects($this->once())
            ->method('batch_basic_publish')
            ->with($message, '', 'test-command-queue');

        $amqpChannel
            ->expects($this->once())
            ->method('publish_batch');

        $this->commandQueue->queueBatch([$command]);
    }

    public function testQueueBatchItShouldThrowWhenInvalidEnvelope(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Queue "test-command-queue" requires a command to be queued, Envelope given');

        $this->commandQueue->queueBatch([$this->getMockBuilder(Envelope::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMockClassName('Envelope')
            ->getMock(), ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->AMQPChannelFactory = $this->createMock(AMQPChannelFactory::class);
        $this->commandQueueWorker = $this->createMock(CommandQueueWorker::class);

        $this->commandQueue = new TestCommandQueue(
            $this->AMQPChannelFactory,
            $this->commandQueueWorker,
        );
    }
}
