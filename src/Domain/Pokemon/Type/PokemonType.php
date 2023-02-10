<?php

namespace App\Domain\Pokemon\Type;

use App\Domain\Pokemon\PokemonElement;

class PokemonType
{
    /**
     * @param array<mixed> $data
     */
    private function __construct(
        private readonly array $data
    ) {
    }

    public function getElement(): PokemonElement
    {
        return PokemonElement::from($this->data['name']);
    }

    /**
     * @return PokemonElement[]
     */
    public function getResistantFor(): array
    {
        return array_map(fn (array $data) => PokemonElement::from($data['name']), $this->data['damage_relations']['double_damage_to']);
    }

    /**
     * @return PokemonElement[]
     */
    public function getWeaknessFor(): array
    {
        return array_map(fn (array $data) => PokemonElement::from($data['name']), $this->data['damage_relations']['double_damage_from']);
    }

    /**
     * @param array<mixed> $map
     */
    public static function fromMap(array $map): self
    {
        return new self($map);
    }
}
