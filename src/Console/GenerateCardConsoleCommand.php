<?php

namespace App\Console;

use App\Domain\AI\GptVersion;
use App\Domain\Card\CardId;
use App\Domain\Card\CardRepository;
use App\Domain\Card\CardType;
use App\Domain\Card\Creature\CreaturePool;
use App\Domain\Card\GenerateCard\GenerateCard;
use App\Domain\Image\FileType;
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
    ) {
        parent::__construct();
    }

    public function configure()
    {
        parent::configure();

        $this
            ->addOption('cardType', 't', InputOption::VALUE_OPTIONAL, 'The card type you want to generate, omit to use a random one. Valid options are '.implode(', ', array_map(fn (CardType $cardType) => $cardType->value, CardType::cases())))
            ->addOption('rarity', 'r', InputOption::VALUE_OPTIONAL, 'The rarity of the Pokémon you want to generate, omit to use a random one. Valid options are '.implode(', ', array_map(fn (PokemonRarity $rarity) => $rarity->value, PokemonRarity::cases())))
            ->addOption('size', 's', InputOption::VALUE_OPTIONAL, 'The size of the Pokémon you want to generate, omit to use a random one. Valid options are '.implode(', ', array_map(fn (PokemonSize $size) => $size->value, PokemonSize::cases())))
            ->addOption('creature', 'c', InputOption::VALUE_OPTIONAL, 'The creature the Pokémon needs to look like (e.g. monkey, dragon, etc.). Omit to to use a random one')
            ->addOption('evolutionSeries', 'e', InputOption::VALUE_NONE, 'Indicates if you want to generate a series that evolve from one another. Options "size", "rarity" and "numberOfCards" will be ignored')
            ->addOption('numberOfCards', 'x', InputOption::VALUE_OPTIONAL, 'The number of cards to generate. A number between 1 and 10', 1)
            ->addOption('fileType', 'f', InputOption::VALUE_OPTIONAL, 'The image file type you want to use. Valid options are '.implode(', ', array_map(fn (FileType $fileType) => $fileType->value, FileType::cases())), FileType::PNG->value)
            ->addOption('gptVersion', 'g', InputOption::VALUE_OPTIONAL, 'GPT version to use. Valid options are '.implode(', ', array_map(fn (GptVersion $gptVersion) => $gptVersion->value, GptVersion::cases())), GptVersion::FOUR->value);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $cardTypes = CardType::cases();
            $rarities = PokemonRarity::cases();
            $sizes = PokemonSize::cases();
            $fileType = FileType::from($input->getOption('fileType'));
            $gptVersion = GptVersion::from((int) $input->getOption('gptVersion'));
            $generateEvolutionSeries = $input->getOption('evolutionSeries');
            $numberOfCardsToGenerate = $generateEvolutionSeries ? (mt_rand(0, 100) < 75 ? 3 : 2) : $input->getOption('numberOfCards');

            if ($numberOfCardsToGenerate < 1 || $numberOfCardsToGenerate > 10) {
                throw new \RuntimeException('numberOfCards must be a valid number between 1 and 10');
            }

            $cardType = $input->getOption('cardType') ? CardType::from($input->getOption('cardType')) : $cardTypes[array_rand($cardTypes)];
            $creature = $input->getOption('creature') ? CreaturePool::matchBySubject(Name::fromString($input->getOption('creature'))) : CreaturePool::randomByCardType($cardType);

            for ($i = 0; $i < $numberOfCardsToGenerate; ++$i) {
                if (!$generateEvolutionSeries) {
                    $cardType = $input->getOption('cardType') ? CardType::from($input->getOption('cardType')) : $cardTypes[array_rand($cardTypes)];
                    $creature = $input->getOption('creature') ? CreaturePool::matchBySubject(Name::fromString($input->getOption('creature'))) : CreaturePool::randomByCardType($cardType);
                    $rarity = $input->getOption('rarity') ? PokemonRarity::from($input->getOption('rarity')) : $rarities[array_rand($rarities)];
                    $size = $input->getOption('size') ? PokemonSize::from($input->getOption('size')) : $sizes[array_rand($sizes)];
                }
                if ($generateEvolutionSeries) {
                    if (0 === $i) {
                        $rarity = PokemonRarity::COMMON;
                        $size = PokemonSize::S;
                    }
                    if (1 === $i && 2 === $numberOfCardsToGenerate) {
                        $rarity = PokemonRarity::RARE;
                        $size = PokemonSize::L;
                    }
                    if (1 === $i && 3 === $numberOfCardsToGenerate) {
                        $rarity = PokemonRarity::UNCOMMON;
                        $size = PokemonSize::M;
                    }
                    if (2 === $i) {
                        $rarity = PokemonRarity::RARE;
                        $size = PokemonSize::XL;
                    }
                }

                $table = new Table($output);
                $table
                    ->setHeaders([[new TableCell('Generating a Pokémon card with following options. This might take up to 3 minutes...', ['colspan' => 2])]])
                    ->setRows([
                        ['Card type', sprintf('<fg=%s>●</> %s', $cardType->getColor(), ucfirst($cardType->value))],
                        ['Pokémon rarity', ucfirst($rarity->value)],
                        ['Pokémon size', strtoupper($size->value)],
                        ['Creature', ucfirst($creature->getName())],
                        ['File type', $fileType->value],
                        ['GPT version', $gptVersion->value],
                    ])
                    ->render();

                $cardId = CardId::random();
                $this->commandBus->dispatch(new GenerateCard(
                    $cardId,
                    $cardType,
                    $rarity,
                    $size,
                    $creature,
                    $fileType,
                    $gptVersion,
                ));

                $card = $this->cardRepository->find($cardId);
                $table = new Table($output);
                $table
                    ->setColumnMaxWidth(1, 100)
                    ->setHeaders([[new TableCell('Used prompts', ['colspan' => 2])]])
                    ->addRow(['Name', $card->getPromptForPokemonName()."\n"])
                    ->addRow(['Description', $card->getPromptForPokemonDescription()."\n"])
                    ->addRow(['Visual', $card->getPromptForVisual()."\n"])
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
                    ->addRow(['Name', $card->getGeneratedName()."\n"])
                    ->addRow(['Description', $card->getGeneratedDescription()."\n"])
                    ->addRow(['Card', sprintf('http://localhost:8080/cards/%s.%s', $cardId, $card->getFileType()->value)])
                    ->render();
            }
        } catch (\Throwable $e) {
            throw new RuntimeException($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
