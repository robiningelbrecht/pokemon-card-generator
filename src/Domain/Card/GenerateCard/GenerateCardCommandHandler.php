<?php

namespace App\Domain\Card\GenerateCard;

use App\Domain\AI\OpenAI;
use App\Domain\AI\PromptGenerator;
use App\Domain\AI\Replicate;
use App\Domain\Card\CardRandomizer;
use App\Domain\Card\CardRepository;
use App\Domain\Card\CardType;
use App\Domain\Card\Creature\CreaturePool;
use App\Domain\Pokemon\Move\PokemonMoveRepository;
use App\Domain\Pokemon\Type\PokemonTypeRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;
use App\Infrastructure\ValueObject\String\Svg;
use Twig\Environment;

#[AsCommandHandler]
class GenerateCardCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly PokemonTypeRepository $pokemonTypeRepository,
        private readonly PokemonMoveRepository $pokemonMoveRepository,
        private readonly CardRandomizer $cardRandomizer,
        private readonly CardRepository $cardRepository,
        private readonly Environment $twig,
        private readonly OpenAI $openAI,
        private readonly Replicate $replicate,
    )
    {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof GenerateCard);

        $type = $this->pokemonTypeRepository->findOneByCardType($command->getCardType());
        $normalType = $this->pokemonTypeRepository->findOneByCardType(CardType::NORMAL);
        $moves = $this->pokemonMoveRepository->findByTypeAndRarity($type, $command->getPokemonRarity());

        $selectedMoves = [$moves[array_rand($moves)]];
        $numberOfMoves = mt_rand(1, 2);
        if (2 === $numberOfMoves) {
            $moves = $this->pokemonMoveRepository->findByTypeAndRarity(mt_rand(0, 1) ? $normalType : $type);
            $selectedMoves[] = $moves[array_rand($moves)];
        }

        $promptGenerator = PromptGenerator::createFor(
            $command->getCardType(),
            $command->getPokemonRarity(),
            $command->getPokemonSize(),
            $command->getCreature(),
        );

        $promptForPokemonName = $promptGenerator->forPokemonName();
        $name = Name::fromString(rtrim($this->openAI->createCompletion($promptForPokemonName), '.'));

        $promptForPokemonDescription = $promptGenerator->forPokemonDescription($name, $selectedMoves);
        $description = Description::fromStringWithMaxChars($this->openAI->createCompletion($promptForPokemonDescription), 145);

        $promptForPokemonVisual = $promptGenerator->forPokemonVisual();
        $prediction = $this->replicate->predict($promptGenerator->forPokemonVisual());

        $countVisualChecks = 0;
        do {
            if ($countVisualChecks > 5) {
                // Image should've been generated a long time ago, something has gone wrong.
                throw new \RuntimeException('Could not generate a visual, API call took too long to finish');
            }
            // Generating image might take some time.
            sleep(7);
            $uriToGeneratedVisual = $this->replicate->getPrediction($prediction['id'])['output'][0] ?? null;
            ++$countVisualChecks;
        } while (!$uriToGeneratedVisual);

        $template = $this->twig->load('card.html.twig');
        $svg = $template->render([
            'name' => $name,
            'hp' => $this->cardRandomizer->randomizeHpByRarity($command->getPokemonRarity()),
            'visual' => $uriToGeneratedVisual,
            'element' => $type->getElement()->value,
            'weakness' => $type->getWeaknessFor(),
            'resistance' => $type->getResistantFor(),
            'retreatCost' => $command->getPokemonRarity()->getRetreatCost(),
            'height' => $this->cardRandomizer->randomizeHeightBySize($command->getPokemonSize()),
            'weight' => $this->cardRandomizer->randomizeWeightBySize($command->getPokemonSize()),
            'description' => $description,
            'moves' => $selectedMoves,
        ]);

        $this->cardRepository->save(
            $command->getCardId(),
            Svg::fromString($svg),
            $promptForPokemonName,
            $promptForPokemonDescription,
            $promptForPokemonVisual,
            $name,
            $description
        );
    }
}
