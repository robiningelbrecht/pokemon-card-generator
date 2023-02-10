<?php

namespace App\Console;

use App\Infrastructure\AMQP\Consumer;
use App\Infrastructure\AMQP\Queue\QueueContainer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'amqp:consume', description: 'Start consuming a given AMQP queue')]
class AmqpConsumeConsoleCommand extends Command implements SignalableCommandInterface
{
    public function __construct(
        private readonly QueueContainer $queueContainer,
        private readonly Consumer $consumer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('queue', InputArgument::REQUIRED, 'The queue to consume.'),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queue = $this->queueContainer->getQueue($input->getArgument('queue'));
        $this->consumer->consume($queue);

        return Command::SUCCESS;
    }

    public function getSubscribedSignals(): array
    {
        return [SIGTERM, SIGINT];
    }

    public function handleSignal(int $signal): void
    {
        $this->consumer->shutdown();
    }
}
