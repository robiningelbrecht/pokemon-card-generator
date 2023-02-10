<?php

namespace App\Tests\Infrastructure\AMQP\Queue;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Queue\QueueContainer;
use App\Tests\ContainerTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class QueueContainerTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private QueueContainer $queueContainer;

    public function testItRegistersAllQueues()
    {
        $this->assertMatchesJsonSnapshot(array_map(function (Queue $queue) {
            return [
                'queueName' => $queue->getName(),
                'workerName' => $queue->getWorker()->getName(),
                'numberOfConsumers' => $queue->getNumberOfConsumers(),
            ];
        }, $this->queueContainer->getQueues()));
    }

    public function testGetQueue(): void
    {
        $queue = new TestQueue($this->createMock(AMQPChannelFactory::class));
        $this->queueContainer->registerQueue($queue);

        $this->assertEquals(
            $queue,
            $this->queueContainer->getQueue('test-queue')
        );
    }

    public function testItShouldThrowWhenGettingInvalidQueueName()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Queue "random-queue" not registered in container');
        $this->queueContainer->getQueue('random-queue');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->queueContainer = $this->getContainer()->get(QueueContainer::class);
    }
}
