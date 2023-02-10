<?php

namespace App\Tests\Infrastructure\AMQP;

use App\Infrastructure\AMQP\AMQPChannelFactory;
use App\Infrastructure\AMQP\AMQPStreamConnectionFactory;
use App\Infrastructure\AMQP\Consumer;
use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\Worker;
use App\Infrastructure\AMQP\Worker\WorkerMaxLifeTimeOrIterationsExceeded;
use App\Tests\Infrastructure\AMQP\RunUnitTest\RunUnitTest;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ConsumerTest extends TestCase
{
    use MatchesSnapshots;

    private Consumer $consumer;
    private MockObject $AMQPStreamConnectionFactory;
    private MockObject $AMQPChannelFactory;

    public function testConsume()
    {
        $queue = $this->createMock(Queue::class);

        $channel = $this->createMock(AMQPChannel::class);
        $this->AMQPChannelFactory
            ->expects($this->once())
            ->method('getForQueue')
            ->with($queue)
            ->willReturn($channel);

        $message = new AMQPMessage(
            'message',
            ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $message->setChannel($channel);
        $message->setDeliveryInfo('tag', false, null, null);

        $channel
            ->expects($this->once())
            ->method('basic_consume')
            ->with($queue->getName(), '', false, false, false, false)
            ->willReturnCallback(function () use ($message, &$callbackCalled) {
                self::assertEquals('message', $message->getBody());
                $callbackCalled = true;
            });

        $matcher = $this->exactly(2);
        $channel
            ->expects($matcher)
            ->method('is_open')
            ->willReturnCallback(function () use ($matcher) {
                if (1 === $matcher->getInvocationCount()) {
                    return true;
                }

                return false;
            });

        $channel
            ->expects($this->never())
            ->method('close');

        $this->AMQPStreamConnectionFactory
            ->expects($this->never())
            ->method('get');

        $this->consumer->consume($queue);
        $this->assertTrue($callbackCalled);
    }

    public function testConsumeOnWorkerMaxLifeTimeOrIterationsExceeded()
    {
        $queue = $this->createMock(Queue::class);

        $channel = $this->createMock(AMQPChannel::class);
        $this->AMQPChannelFactory
            ->expects($this->once())
            ->method('getForQueue')
            ->with($queue)
            ->willReturn($channel);

        $message = new AMQPMessage(
            'message',
            ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $message->setChannel($channel);
        $message->setDeliveryInfo('tag', false, null, null);

        $channel
            ->expects($this->once())
            ->method('basic_consume')
            ->with($queue->getName(), '', false, false, false, false)
            ->willReturnCallback(function () use ($message, &$callbackCalled) {
                self::assertEquals('message', $message->getBody());
                $callbackCalled = true;
                throw new WorkerMaxLifeTimeOrIterationsExceeded();
            });

        $channel
            ->expects($this->never())
            ->method('is_open');

        $channel
            ->expects($this->once())
            ->method('close');

        $this->AMQPStreamConnectionFactory
            ->expects($this->once())
            ->method('get');

        $this->consumer->consume($queue);
        $this->assertTrue($callbackCalled);
    }

    public function testConsumeCallback()
    {
        $envelope = new RunUnitTest();
        $channel = $this->createMock(AMQPChannel::class);
        $queue = $this->createMock(Queue::class);
        $worker = $this->createMock(Worker::class);

        $message = new AMQPMessage(
            serialize($envelope),
            ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $message->setChannel($channel);
        $message->setDeliveryInfo('tag', false, null, null);

        $queue
            ->expects($this->once())
            ->method('getWorker')
            ->willReturn($worker);

        $worker
            ->expects($this->once())
            ->method('processMessage')
            ->with($envelope, $message);

        $channel
            ->expects($this->once())
            ->method('basic_ack')
            ->with('tag');

        Consumer::consumeCallback(
            $message,
            $queue
        );
    }

    public function testConsumeCallbackWorkerMaxLifeTimeOrIterationsExceeded()
    {
        $channel = $this->createMock(AMQPChannel::class);
        $queue = $this->createMock(Queue::class);
        $worker = $this->createMock(Worker::class);

        $message = new AMQPMessage(
            serialize(new RunUnitTest()),
            ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $message->setChannel($channel);
        $message->setDeliveryInfo('tag', false, null, null);

        $queue
            ->expects($this->once())
            ->method('getWorker')
            ->willReturn($worker);

        $worker
            ->expects($this->once())
            ->method('processMessage')
            ->willThrowException(new WorkerMaxLifeTimeOrIterationsExceeded());

        $channel
            ->expects($this->once())
            ->method('basic_nack')
            ->with('tag', false, true);

        $channel
            ->expects($this->never())
            ->method('basic_ack');

        $this->expectException(WorkerMaxLifeTimeOrIterationsExceeded::class);

        Consumer::consumeCallback(
            $message,
            $queue
        );
    }

    public function testConsumeCallbackOnException()
    {
        $envelope = new RunUnitTest();
        $channel = $this->createMock(AMQPChannel::class);
        $queue = $this->createMock(Queue::class);
        $worker = $this->createMock(Worker::class);

        $message = new AMQPMessage(
            serialize($envelope),
            ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $message->setChannel($channel);
        $message->setDeliveryInfo('tag', false, null, null);

        $queue
            ->expects($this->once())
            ->method('getWorker')
            ->willReturn($worker);

        $exception = new \RuntimeException();
        $worker
            ->expects($this->once())
            ->method('processMessage')
            ->willThrowException($exception);

        $worker
            ->expects($this->once())
            ->method('processFailure')
            ->with($envelope, $message, $exception, $queue);

        $channel
            ->expects($this->once())
            ->method('basic_ack')
            ->with('tag');

        Consumer::consumeCallback(
            $message,
            $queue
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->AMQPStreamConnectionFactory = $this->createMock(AMQPStreamConnectionFactory::class);
        $this->AMQPChannelFactory = $this->createMock(AMQPChannelFactory::class);

        $this->consumer = new Consumer(
            $this->AMQPStreamConnectionFactory,
            $this->AMQPChannelFactory,
        );
    }
}
