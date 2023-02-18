<?php

namespace App\Domain\Card\GenerateCard;

use App\Domain\AI\OpenAI;
use App\Domain\AI\PromptGenerator;
use App\Domain\AI\Replicate;
use App\Domain\Card\Card;
use App\Domain\Card\CardRandomizer;
use App\Domain\Card\CardRepository;
use App\Domain\Card\CardType;
use App\Domain\Image\ImageGeneratorFactory;
use App\Domain\Pokemon\Move\PokemonMoveRepository;
use App\Domain\Pokemon\Type\PokemonTypeRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use Lcobucci\Clock\Clock;

#[AsCommandHandler]
class GenerateCardCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly PokemonTypeRepository $pokemonTypeRepository,
        private readonly PokemonMoveRepository $pokemonMoveRepository,
        private readonly CardRandomizer $cardRandomizer,
        private readonly CardRepository $cardRepository,
        private readonly OpenAI $openAI,
        private readonly Replicate $replicate,
        private readonly Clock $clock,
        private readonly ImageGeneratorFactory $imageGeneratorFactory,
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof GenerateCard);

        $type = $this->pokemonTypeRepository->findOneByCardType($command->getCardType());
        $normalType = $this->pokemonTypeRepository->findOneByCardType(CardType::NORMAL);
        $moves = $this->pokemonMoveRepository->findByTypeAndRarity($type, $command->getPokemonRarity());

        $firstMove = $moves[array_rand($moves)];
        $selectedMoves = [$firstMove];
        $numberOfMoves = mt_rand(1, 2);
        if (2 === $numberOfMoves) {
            $moves = $this->pokemonMoveRepository->findByTypeAndRarity(mt_rand(0, 1) ? $normalType : $type);
            do {
                $secondMove = $moves[array_rand($moves)];
            } while ($firstMove->getName() === $secondMove->getName());
            $selectedMoves[] = $secondMove;
        }

        $promptGenerator = PromptGenerator::createFor(
            $command->getCardType(),
            $command->getPokemonRarity(),
            $command->getPokemonSize(),
            $command->getCreature(),
        );

        $promptForPokemonName = $promptGenerator->forPokemonName();
        $name = Name::fromString(ucfirst(strtolower(rtrim($this->openAI->createCompletion($promptForPokemonName), '.'))));

        $promptForPokemonDescription = $promptGenerator->forPokemonDescription($name, $selectedMoves);
        $description = Description::fromStringWithMaxChars($this->openAI->createCompletion($promptForPokemonDescription), 145);

        $promptForPokemonVisual = $promptGenerator->forPokemonVisual();
        $prediction = $this->replicate->predict($promptGenerator->forPokemonVisual());

        $countVisualChecks = 0;
        do {
            if ($countVisualChecks > 30) {
                // Image should've been generated, something has gone wrong.
                throw new \RuntimeException('Could not generate a visual, API call took too long to finish');
            }
            // Generating image might take some time.
            sleep(6);
            $uriToGeneratedVisual = $this->replicate->getPrediction($prediction['id'])['output'][0] ?? null;
            ++$countVisualChecks;
        } while (!$uriToGeneratedVisual);

        $this->cardRepository->save(
            Card::create(
                $command->getCardId(),
                $command->getCardType(),
                $promptForPokemonName,
                $promptForPokemonDescription,
                $promptForPokemonVisual,
                $name,
                $description,
                $command->getFileType(),
                $this->clock->now()
            ),
            $this->imageGeneratorFactory->getForFileType($command->getFileType())->make(
                $name,
                $type,
                $command->getPokemonRarity(),
                $description,
                $uriToGeneratedVisual,
                $this->cardRandomizer->randomizeHpByRarity($command->getPokemonRarity()),
                $selectedMoves,
                $this->cardRandomizer->randomizeHeightBySize($command->getPokemonSize()),
                $this->cardRandomizer->randomizeWeightBySize($command->getPokemonSize())
            ),
        );
    }
}
