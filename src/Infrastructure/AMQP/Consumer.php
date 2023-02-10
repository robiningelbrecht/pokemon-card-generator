<?php

namespace App\Infrastructure\AMQP;

use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\WorkerMaxLifeTimeOrIterationsExceeded;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{
    private ?AMQPChannel $channel = null;
    private bool $forceShutDown = false;

    public function __construct(
        private readonly AMQPStreamConnectionFactory $AMQPStreamConnectionFactory,
        private readonly AMQPChannelFactory $AMQPChannelFactory,
    ) {
    }

    public function __destruct()
    {
        $this->channel?->close();
    }

    public function shutdown(): void
    {
        $this->forceShutDown = true;
    }

    public function consume(Queue $queue): void
    {
        $channel = $this->AMQPChannelFactory->getForQueue($queue);

        $callback = static function (AMQPMessage $message) use ($queue) {
            // Block any incoming exit signals to make sure the current message can be processed.
            pcntl_sigprocmask(SIG_BLOCK, [SIGTERM, SIGINT]);
            Consumer::consumeCallback($message, $queue);
            // Unblock any incoming exit signals, message has been processed, consumer can DIE.
            pcntl_sigprocmask(SIG_UNBLOCK, [SIGTERM, SIGINT]);
            // Dispatch the exit signals that might've come in.
            pcntl_signal_dispatch();
        };

        try {
            $channel->basic_consume($queue->getName(), '', false, false, false, false, $callback);

            while ($channel->is_open() && !$this->forceShutDown) {
                $channel->wait();
                // Dispatch incoming exit signals.
                pcntl_signal_dispatch();
            }
        } catch (WorkerMaxLifeTimeOrIterationsExceeded) {
            $channel->close();
            $this->AMQPStreamConnectionFactory->get()->close();
        }
    }

    public static function consumeCallback(
        AMQPMessage $message,
        Queue $queue): void
    {
        $worker = $queue->getWorker();
        $envelope = unserialize($message->getBody());

        try {
            if ($worker->maxLifeTimeReached() || $worker->maxIterationsReached()) {
                throw new WorkerMaxLifeTimeOrIterationsExceeded();
            }

            $worker->processMessage($envelope, $message);
            $message->getChannel()?->basic_ack($message->getDeliveryTag());
        } catch (WorkerMaxLifeTimeOrIterationsExceeded $exception) {
            // Requeue message to make sure next consumer can process it.
            $message->getChannel()?->basic_nack($message->getDeliveryTag(), false, true);
            throw $exception;
        } catch (\Throwable $exception) {
            $worker->processFailure($envelope, $message, $exception, $queue);
            // Ack the message to unblock queue. Worker should handle failed messages.
            $message->getChannel()?->basic_ack($message->getDeliveryTag());
        }
    }
}
