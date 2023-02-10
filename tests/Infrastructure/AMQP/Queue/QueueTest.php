<?php

namespace App\Tests\Infrastructure\AMQP\Queue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Tests\Infrastructure\AMQP\RunUnitTest\RunUnitTest;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    private TestQueue $testQueue;
    private MockObject $AMQPChannelFactory;

    public function testQueue()
    {
        $envelope = new RunUnitTest();

        $channel = $this->createMock(AMQPChannel::class);
        $this->AMQPChannelFactory
            ->expects($this->once())
            ->method('getForQueue')
            ->with($this->testQueue, null)
            ->willReturn($channel);

        $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
        $message = new AMQPMessage(serialize($envelope), $properties);

        $channel
            ->expects($this->once())
            ->method('basic_publish')
            ->with($message, null, $this->testQueue->getName());

        $this->testQueue->queue($envelope);
    }

    public function testQueueBatch()
    {
        $envelope = new RunUnitTest();

        $channel = $this->createMock(AMQPChannel::class);
        $this->AMQPChannelFactory
            ->expects($this->once())
            ->method('getForQueue')
            ->with($this->testQueue, null)
            ->willReturn($channel);

        $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
        $message = new AMQPMessage(serialize($envelope), $properties);

        $channel
            ->expects($this->exactly(2))
            ->method('batch_basic_publish')
            ->with($message, null, $this->testQueue->getName());

        $channel
            ->expects($this->once())
            ->method('publish_batch');

        $this->testQueue->queueBatch([$envelope, $envelope]);
    }

    public function testQueueBatchWhenEmpty()
    {
        $channel = $this->createMock(AMQPChannel::class);
        $this->AMQPChannelFactory
            ->expects($this->never())
            ->method('getForQueue');

        $channel
            ->expects($this->never())
            ->method('batch_basic_publish');

        $channel
            ->expects($this->never())
            ->method('publish_batch');

        $this->testQueue->queueBatch([]);
    }

    public function testQueueBatchItShouldThrowWhenInvalidEnvelope()
    {
        $channel = $this->createMock(AMQPChannel::class);
        $this->AMQPChannelFactory
            ->expects($this->never())
            ->method('getForQueue');

        $channel
            ->expects($this->never())
            ->method('batch_basic_publish');

        $channel
            ->expects($this->never())
            ->method('publish_batch');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('All envelopes need to implement App\Infrastructure\AMQP\Envelope');

        $this->testQueue->queueBatch(['test']);
    }

    public function testGetNameItShouldThrowWhenNoAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('AsAmqpQueue attribute not set');

        $queue = new TestQueueWithoutAttribute($this->createMock(AMQPChannelFactory::class));
        $queue->getName();
    }

    public function testGetNumberOfWorkersItShouldThrowWhenNoAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('AsAmqpQueue attribute not set');

        $queue = new TestQueueWithoutAttribute($this->createMock(AMQPChannelFactory::class));
        $queue->getNumberOfConsumers();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->AMQPChannelFactory = $this->createMock(AMQPChannelFactory::class);

        $this->testQueue = new TestQueue(
            $this->AMQPChannelFactory
        );
    }
}
