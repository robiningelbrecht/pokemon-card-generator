<?php

namespace App\Domain\Pokemon\Type;

use App\Domain\Card\CardType;
use App\Infrastructure\Exception\EntityNotFound;
use SleekDB\Store;

class PokemonTypeRepository
{
    public function __construct(
        private readonly Store $store
    ) {
    }

    public function find(string $id): PokemonType
    {
        if (!$row = $this->store->findById($id)) {
            throw new EntityNotFound(sprintf('Type "%s" not found', $id));
        }

        return PokemonType::fromMap($row);
    }

    public function findOneByCardType(CardType $card): PokemonType
    {
        if (!$row = $this->store->findOneBy(['name', '==', $card->value])) {
            throw new EntityNotFound(sprintf('Type "%s" not found', $card->value));
        }

        return PokemonType::fromMap($row);
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
