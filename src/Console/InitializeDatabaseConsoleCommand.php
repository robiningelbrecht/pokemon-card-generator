<?php

namespace App\Console;

use App\Domain\Pokemon\Move\PokemonMoveRepository;
use App\Domain\Pokemon\PokeApi;
use App\Domain\Pokemon\Type\PokemonTypeRepository;
use App\Infrastructure\Serialization\Json;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:database:init', description: 'Initialize database with moves and types')]
class InitializeDatabaseConsoleCommand extends Command
{
    public function __construct(
        private readonly PokemonTypeRepository $pokemonTypeRepository,
        private readonly PokemonMoveRepository $pokemonMoveRepository,
        private readonly PokeApi $pokeApi,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 1; $i <= 18; ++$i) {
            // $this->pokemonTypeRepository->save($this->pokeApi->getType($i));
        }

        $cards = [
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/base1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/base2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/base3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/base4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/base5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/base6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bp.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw8.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw9.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw10.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bw11.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/bwp.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/cel25.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/cel25c.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/col1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dc1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/det1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dp7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dpp.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/dv1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ecard1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ecard2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ecard3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex8.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex9.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex10.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex11.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex12.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex13.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex14.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex15.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ex16.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/fut20.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/g1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/gym1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/gym2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/hgss1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/hgss2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/hgss3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/hgss4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/hsp.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd11.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd12.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd14.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd15.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd16.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd17.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd18.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd19.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd21.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/mcd22.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/neo1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/neo2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/neo3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/neo4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/np.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pgo.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pl1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pl2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pl3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pl4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop8.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/pop9.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/ru1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/si1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm8.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm9.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm10.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm11.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm12.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm115.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm35.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sm75.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/sma.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/smp.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh8.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh9.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh10.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh11.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh12.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh10tg.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/swsh11tg.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy0.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy1.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy2.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy3.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy4.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy5.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy6.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy7.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy8.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy9.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy10.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy11.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xy12.json')),
//            ...Json::decode(file_get_contents('https://raw.githubusercontent.com/PokemonTCG/pokemon-tcg-data/master/cards/en/xyp.json')),
        ];

        $attacks = [];
        foreach ($cards as $card) {
            if (empty($card['attacks'])) {
                continue;
            }

            foreach ($card['attacks'] as $attack) {
                if (empty($attack['name'])) {
                    continue;
                }
                if (empty($attack['cost'][0])) {
                    continue;
                }

                $attack['cost'] = array_map(function (string $cost) {
                    $cost = strtolower($cost);
                    if ('lightning' === $cost) {
                        return 'electric';
                    }
                    if ('colorless' === $cost) {
                        return 'normal';
                    }
                    if ('darkness' === $cost) {
                        return 'dark';
                    }
                    if ('metal' === $cost) {
                        return 'steel';
                    }

                    return $cost;
                }, $attack['cost']);

                $attack['damage'] = (int) preg_replace('/[^0-9]/', '', $attack['damage']);

                $attack['type'] = $attack['cost'][0];
                $attacks[$attack['name']] = $attack;
            }
        }

        $types = [];
        foreach ($attacks as $attack) {
            $types[$attack['type']] = $attack['type'];
            // $this->pokemonMoveRepository->save($attack);
        }

        $output->writeln(implode(' ,', $types));

        return Command::SUCCESS;
    }
}
