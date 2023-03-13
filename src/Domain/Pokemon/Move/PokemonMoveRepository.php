<?php

namespace App\Domain\Pokemon\Move;

use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\Type\PokemonType;
use SleekDB\Store;

class PokemonMoveRepository
{
    public function __construct(
        private readonly Store $store
    ) {
    }

    public function findByTypeAndRarity(PokemonType $type, PokemonRarity $rarity): array
    {
        return array_map(
            fn (array $data) => PokemonMove::fromMap($data),
            $this->store->findBy([
                ['type', '==', $type->getElement()->value],
                [
                    ['damage', 'BETWEEN', $rarity->getAllowedDamageRangeForMove()],
                    'OR',
                    ['damage', '=', 0],
                ],
            ], ['id' => 'ASC'])
        );
    }

    public function save(array $row): void
    {
        $this->store->updateOrInsert($row, false);
    }
}
