<?php

namespace App\Tests\Infrastructure\AMQP\Queue\FailedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\FailedQueue\FailedQueue;
use App\Infrastructure\AMQP\Queue\FailedQueue\FailedQueueFactory;
use App\Tests\Infrastructure\AMQP\Queue\TestQueue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FailedQueueFactoryTest extends TestCase
{
    private FailedQueueFactory $failedQueueFactory;
    private MockObject $AMQPChannelFactory;

    public function testBuildFor()
    {
        $queue = new TestQueue($this->AMQPChannelFactory);
        $expectedFailedQueue = new FailedQueue($queue, $this->AMQPChannelFactory);

        $this->assertEquals(
            $expectedFailedQueue,
            $this->failedQueueFactory->buildFor($queue)
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->AMQPChannelFactory = $this->createMock(AMQPChannelFactory::class);

        $this->failedQueueFactory = new FailedQueueFactory(
            $this->AMQPChannelFactory
        );
    }
}
