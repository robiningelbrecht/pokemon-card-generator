<?php

namespace App\Console;

use App\Domain\Pokemon\Move\PokemonMoveRepository;
use App\Domain\Pokemon\PokeApi;
use App\Domain\Pokemon\Type\PokemonTypeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:database:init', description: 'Initialize database with moves and types')]
class InitializeDatabaseConsoleCommand extends Command
{
    public function __construct(
        private readonly PokemonMoveRepository $pokemonMoveRepository,
        private readonly PokemonTypeRepository $pokemonTypeRepository,
        private readonly PokeApi $pokeApi,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 1; $i <= 900; ++$i) {
            $this->pokemonMoveRepository->save($this->pokeApi->getMove($i));
        }
        for ($i = 1; $i <= 18; ++$i) {
            $this->pokemonTypeRepository->save($this->pokeApi->getType($i));
        }

        return Command::SUCCESS;
    }
}
