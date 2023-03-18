<?php

namespace App\Domain\AI;

use App\Domain\Card\CardType;
use App\Domain\Card\Creature\Creature;
use App\Domain\Card\Environment;
use App\Domain\Pokemon\Move\PokemonMove;
use App\Domain\Pokemon\PokemonRarity;
use App\Domain\Pokemon\PokemonSize;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;

class PromptGenerator
{
    private function __construct(
        private readonly CardType $cardType,
        private readonly PokemonRarity $pokemonRarity,
        private readonly Name $subject,
        private readonly string $sizeAdjective,
        private readonly string $rarityAdjective,
        private readonly Description $detail,
        private readonly Environment $environment,
        private readonly string $ambience,
    ) {
    }

    public function forPokemonName(): Prompt
    {
        return Prompt::fromString(sprintf(
            'Generate a unique, original, creative, %s pokemon name for %s %s. It can be found in %s-like environments. Do not use the words pokemon, %s or %s. Ony answer with the generated name',
            PokemonRarity::UNCOMMON === $this->pokemonRarity ? 'short, single-word' : 'single-word',
            implode(' ', $this->buildSubjectDescription()),
            $this->detail,
            $this->environment,
            $this->cardType->value,
            $this->subject
        ));
    }

    public function forPokemonDescription(
        array $moves,
    ): Prompt {
        $prompt = [
            sprintf(
                'Generate a short, original, creative Pokedex description for a %s pokemon. It can be found in %s-like environments',
                implode(' ', $this->buildSubjectDescription()),
                $this->environment
            ),
            sprintf('It has the following abilities: %s. Be creative about its day-to-day life.', implode(', ', array_map(fn (PokemonMove $move) => $move->getName(), $moves))),
            sprintf('Do not use the word %s or %s. Use a maximum of 150 characters. Only answer with the generated description.', $this->subject, $this->cardType->value),
        ];

        return Prompt::fromString(implode(' ', $prompt));
    }

    public function forPokemonVisual(): Prompt
    {
        // When visual generation is switched to MJ, we can update the prompt to something like:
        // a chibi young fire-type pokemon::1.8, a parrot, in a volcano environment, lava texture background, anime chibi drawing style, pastel background --niji --ar 3:2
        $segments = [
            'mdjrny-v4 style portrait of '.$this->cardType->value.'-type pokemon',
            $this->subject.'-like',
            $this->rarityAdjective,
            'digital art',
            'sugimori',
            'chibi',
            'centered',
            'full body',
            $this->ambience,
        ];

        return Prompt::fromString(implode(', ', $segments));
    }

    private function buildSubjectDescription(): array
    {
        return [
            'a',
            $this->sizeAdjective,
            $this->rarityAdjective,
            $this->subject,
            $this->cardType->value.'-type',
        ];
    }

    public static function createFor(
        CardType $cardType,
        PokemonRarity $pokemonRarity,
        PokemonSize $pokemonSize,
        Creature $creature
    ): self {
        $environments = $cardType->getEnvironments();
        $ambience = $cardType->getAmbience($pokemonRarity);
        $sizeAdjectives = $pokemonSize->getAdjectives();
        $rarityAdjectives = $pokemonRarity->getAdjectives();

        return new self(
            $cardType,
            $pokemonRarity,
            $creature->getName(),
            $sizeAdjectives[array_rand($sizeAdjectives)],
            $rarityAdjectives[array_rand($rarityAdjectives)],
            $creature->getRandomDescriptionForCardType($cardType),
            $environments[array_rand($environments)],
            $ambience[array_rand($ambience)],
        );
    }
}
