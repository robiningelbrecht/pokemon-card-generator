<?php

namespace App\Domain\Pokemon\Move;

use App\Domain\Pokemon\PokemonElement;

class PokemonMove
{
    private function __construct(
        private readonly array $data
    ) {
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getLabel(): string
    {
        return $this->getName();
    }

    public function getDescription(): ?string
    {
        return $this->data['text'];
    }

    /**
     * @return PokemonElement[]
     */
    public function getCost(): array
    {
        // We only support a cost of 4.
        return array_slice(array_map(fn (string $cost) => PokemonElement::from($cost), $this->data['cost']), 0, 4);
    }

    public function getPower(): int
    {
        return (int) $this->data['damage'];
    }

    public static function fromMap(array $map): self
    {
        return new self($map);
    }
}
