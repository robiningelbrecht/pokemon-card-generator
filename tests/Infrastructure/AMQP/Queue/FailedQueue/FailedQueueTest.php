<?php

namespace App\Tests\Infrastructure\AMQP\Queue\FailedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\FailedQueue\FailedQueue;
use App\Tests\Infrastructure\AMQP\Queue\TestQueue;
use PHPUnit\Framework\TestCase;

class FailedQueueTest extends TestCase
{
    private FailedQueue $failedQueue;
    private TestQueue $testQueue;

    public function testGetName()
    {
        $this->assertEquals('test-queue-failed', $this->failedQueue->getName());
        $this->assertEquals(0, $this->failedQueue->getNumberOfConsumers());
    }

    public function testGetWorker()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed queues do not have workers');

        $this->failedQueue->getWorker();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $AMQPChannelFactory = $this->createMock(AMQPChannelFactory::class);
        $this->testQueue = new TestQueue($AMQPChannelFactory);

        $this->failedQueue = new FailedQueue(
            $this->testQueue,
            $AMQPChannelFactory
        );
    }
}
