<?php

namespace App\Tests\Infrastructure\AMQP\Queue\DelayedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\DelayedQueue\DelayedQueue;
use App\Infrastructure\AMQP\Queue\DelayedQueue\DelayedQueueFactory;
use App\Tests\Infrastructure\AMQP\Queue\TestQueue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DelayedQueueFactoryTest extends TestCase
{
    private DelayedQueueFactory $delayedQueueFactory;
    private MockObject $AMQPChannelFactory;

    public function testBuildWithDelayForQueue(): void
    {
        $this->assertInstanceOf(
            DelayedQueue::class,
            $this->delayedQueueFactory->buildWithDelayForQueue(10, new TestQueue($this->AMQPChannelFactory))
        );

        $this->assertEquals(new DelayedQueue(
            new TestQueue($this->AMQPChannelFactory),
            60,
            $this->AMQPChannelFactory
        ), $this->delayedQueueFactory->buildWithDelayForQueue(60, new TestQueue($this->AMQPChannelFactory)));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->AMQPChannelFactory = $this->createMock(AMQPChannelFactory::class);

        $this->delayedQueueFactory = new DelayedQueueFactory(
            $this->AMQPChannelFactory,
        );
    }
}
