<?php

namespace App\Tests\Infrastructure\AMQP;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\AMQPChannelOptions;
use App\Infrastructure\AMQP\AMQPStreamConnectionFactory;
use App\Infrastructure\AMQP\Queue\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AMQPChannelFactoryTest extends TestCase
{
    private AMQPChannelFactory $AMQPChannelFactory;
    private MockObject $AMQPStreamConnectionFactory;

    public function testGetForQueueAndDefaultOptions()
    {
        $queue = $this->createMock(Queue::class);
        $queue
            ->expects($this->any())
            ->method('getName')
            ->willReturn('test-queue');

        $connection = $this->createMock(AMQPStreamConnection::class);
        $this->AMQPStreamConnectionFactory
            ->expects($this->once())
            ->method('get')
            ->willReturn($connection);

        $channel = $this->createMock(AMQPChannel::class);
        $connection
            ->expects($this->once())
            ->method('channel')
            ->willReturn($channel);

        $channel
            ->expects($this->once())
            ->method('queue_declare')
            ->with($queue->getName(), false, true, false, false, false, [], null);

        $channel
            ->expects($this->once())
            ->method('basic_qos')
            ->with(null, 1, null);

        $this->assertEquals($channel, $this->AMQPChannelFactory->getForQueue($queue));
        // Call again to verify static cache.
        $this->AMQPChannelFactory->getForQueue($queue);
    }

    public function testGetForQueueWithNonDefaultOptions()
    {
        $queue = $this->createMock(Queue::class);
        $queue
            ->expects($this->any())
            ->method('getName')
            ->willReturn('test-queue');

        $connection = $this->createMock(AMQPStreamConnection::class);
        $this->AMQPStreamConnectionFactory
            ->expects($this->once())
            ->method('get')
            ->willReturn($connection);

        $channel = $this->createMock(AMQPChannel::class);
        $connection
            ->expects($this->once())
            ->method('channel')
            ->willReturn($channel);

        $channel
            ->expects($this->once())
            ->method('queue_declare')
            ->with($queue->getName(), true, true, true, false, false, [1, 2, 3], 3);

        $channel
            ->expects($this->once())
            ->method('basic_qos')
            ->with(null, 1, null);

        $this->assertEquals($channel, $this->AMQPChannelFactory->getForQueue($queue, new AMQPChannelOptions(
            true,
            true,
            true,
            false,
            false,
            [1, 2, 3],
            3
        )));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->AMQPStreamConnectionFactory = $this->createMock(AMQPStreamConnectionFactory::class);

        $this->AMQPChannelFactory = new AMQPChannelFactory(
            $this->AMQPStreamConnectionFactory
        );
    }
}
