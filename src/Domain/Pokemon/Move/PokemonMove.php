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
        $names = array_filter($this->data['names'], fn (array $name) => 'en' === $name['language']['name']);

        return reset($names)['name'];
    }

    public function getDescription(): ?string
    {
        if (!$descriptions = array_filter($this->data['effect_entries'], fn (array $entry) => 'en' === $entry['language']['name'])) {
            return null;
        }

        $effect = reset($descriptions)['effect'];

        return str_replace('$effect_chance', $this->data['effect_chance'] ?? '', $effect);
    }

    public function getCost(): array
    {
        $element = PokemonElement::from($this->data['type']['name']);

        if ($this->getPp() <= 5) {
            return [
                $element, $element, $element, $element,
            ];
        }
        if ($this->getPp() <= 15) {
            return [
                $element, $element, $element,
            ];
        }
        if ($this->getPp() <= 30) {
            return [
                $element, $element,
            ];
        }

        return [
            $element,
        ];
    }

    public function getPp(): int
    {
        return (int) $this->data['pp'];
    }

    public function getPower(): int
    {
        return (int) $this->data['power'];
    }

    public static function fromMap(array $map): self
    {
        return new self($map);
    }
}
