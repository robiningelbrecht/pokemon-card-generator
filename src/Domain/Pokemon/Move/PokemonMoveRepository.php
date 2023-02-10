<?php

namespace App\Domain\Pokemon\Move;

use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\Type\PokemonType;
use App\Infrastructure\Exception\EntityNotFound;
use SleekDB\Store;

class PokemonMoveRepository
{
    public function __construct(
        private readonly Store $store
    ) {
    }

    public function find(string $id): PokemonMove
    {
        if (!$row = $this->store->findById($id)) {
            throw new EntityNotFound(sprintf('Move "%s" not found', $id));
        }

        return PokemonMove::fromMap($row);
    }

    public function findByTypeAndRarity(PokemonType $type, PokemonRarity $rarity = null): array
    {
        return array_map(
            fn (array $data) => PokemonMove::fromMap($data),
            $this->store->findBy([
                ['type.name', '==', $type->getElement()->value],
                ['pp', '>=', $rarity ? $rarity->getMinAllowedPpForMove() : -1],
            ], ['id' => 'ASC'])
        );
    }

    public function save(array $row): void
    {
        $this->store->updateOrInsert($row, false);
    }

    public function saveMany(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $this->store->updateOrInsertMany($rows, false);
    }
}
