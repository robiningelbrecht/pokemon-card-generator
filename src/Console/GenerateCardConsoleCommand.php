<?php

namespace App\Console;

use App\Domain\Card\CardId;
use App\Domain\Card\CardRepository;
use App\Domain\Card\CardType;
use App\Domain\Card\Creature\CreaturePool;
use App\Domain\Card\GenerateCard\GenerateCard;
use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\PokemonSize;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\ValueObject\String\Name;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:card:generate', description: 'Generate a Pokémon card')]
class GenerateCardConsoleCommand extends Command
{
    public function __construct(
        public readonly CommandBus $commandBus,
        private readonly CardRepository $cardRepository
    )
    {
        parent::__construct();
    }

    public function configure()
    {
        parent::configure();

        $this
            ->addOption('cardType', 't', InputOption::VALUE_OPTIONAL, 'The card type you want to generate, omit to use a random one. Valid options are ' . implode(', ', array_map(fn(CardType $cardType) => $cardType->value, CardType::cases())))
            ->addOption('rarity', 'r', InputOption::VALUE_OPTIONAL, 'The rarity of the Pokémon you want to generate, omit to use a random one. Valid options are ' . implode(', ', array_map(fn(PokemonRarity $rarity) => $rarity->value, PokemonRarity::cases())))
            ->addOption('size', 's', InputOption::VALUE_OPTIONAL, 'The size of the Pokémon you want to generate, omit to use a random one. Valid options are ' . implode(', ', array_map(fn(PokemonSize $size) => $size->value, PokemonSize::cases())))
            ->addOption('creature', 'c', InputOption::VALUE_OPTIONAL, 'The creature the Pokémon needs to look like (e.g. monkey, dragon, etc.). Omit to to use a random one');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $cardTypes = CardType::cases();
            $rarities = PokemonRarity::cases();
            $sizes = PokemonSize::cases();

            $cardType = $input->getOption('cardType') ? CardType::from($input->getOption('cardType')) : $cardTypes[array_rand($cardTypes)];
            $rarity = $input->getOption('rarity') ? PokemonRarity::from($input->getOption('rarity')) : $rarities[array_rand($rarities)];
            $size = $input->getOption('size') ? PokemonSize::from($input->getOption('size')) : $sizes[array_rand($sizes)];
            $creature = $input->getOption('creature') ? CreaturePool::matchBySubject($input->getOption('creature')) : CreaturePool::randomByCardType($cardType);

            $table = new Table($output);
            $table
                ->setHeaders([[new TableCell('Generating a Pokémon card with following options. This might take a few seconds...', ['colspan' => 2])]])
                ->setRows([
                    ['Card type', sprintf('<fg=%s>●</> %s', $cardType->getColor(), ucfirst($cardType->value))],
                    ['Pokémon rarity', ucfirst($rarity->value)],
                    ['Pokémon size', strtoupper($size->value)],
                    ['Creature', $creature->getName()],
                ])
                ->render();

            $cardId = CardId::random();
            $this->commandBus->dispatch(new GenerateCard(
                $cardId,
                $cardType,
                $rarity,
                $size,
                $creature,
            ));

            $metadata = $this->cardRepository->find($cardId)['metadata'];
            $table = new Table($output);
            $table
                ->setColumnMaxWidth(1, 100)
                ->setHeaders([[new TableCell('Used prompts', ['colspan' => 2])]])
                ->addRow(['Name', $metadata['promptForName'] . "\n"])
                ->addRow(['Description', $metadata['promptForDescription'] . "\n"])
                ->addRow(['Visual', $metadata['promptForVisual'] . "\n"])
                ->addRow(new TableSeparator())
                ->addRow([new TableCell(
                    'Generated content',
                    [
                        'colspan' => 2,
                        'style' => new TableCellStyle([
                            'cellFormat' => '<info>%s</info>',
                        ]),
                    ]
                )])
                ->addRow(new TableSeparator())
                ->addRow(['Name', $metadata['generatedName'] . "\n"])
                ->addRow(['Description', $metadata['generatedDescription'] . "\n"])
                ->addRow(['Card', sprintf('http://localhost:8080/cards/%s.svg', $cardId)])
                ->render();
        } catch (\Throwable $e) {
            throw new RuntimeException($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
