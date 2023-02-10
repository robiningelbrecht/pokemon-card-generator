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
        private readonly array $subjectAdjectives,
        private readonly Description $detail,
        private readonly Environment $environment,
        private readonly string $ambience,
    ) {
    }

    public function forPokemonName(): Prompt
    {
        return Prompt::fromString(sprintf(
            'Generate a unique, original, creative, %s pokemon name for %s %s. It can be found in %s-like environments. Do not use the words pokemon or %s:',
            PokemonRarity::UNCOMMON === $this->pokemonRarity ? 'short, single-word' : 'single-word',
            implode(' ', $this->buildSubjectDescription()),
            $this->detail,
            $this->environment,
            $this->cardType->value
        ));
    }

    public function forPokemonDescription(
        Name $pokemonName,
        array $moves,
    ): Prompt {
        $prompt = [
            sprintf(
                'Generate a short, original, creative Pokedex description for %s, %s pokemon. It can be found in %s-like environments',
                $pokemonName,
                implode(' ', $this->buildSubjectDescription()),
                $this->environment
            ),
            sprintf('It has the following abilities: %s. Be creative about its day-to-day life.', implode(', ', array_map(fn (PokemonMove $move) => $move->getName(), $moves))),
            sprintf('Do not use the word %s or %s or the ability names. Use a maximum of 50 characters:', $this->subject, $this->cardType->value),
        ];

        return Prompt::fromString(implode(' ', $prompt));
    }

    public function forPokemonVisual(): Prompt
    {
        // When visual generation is switched to MJ, we can update the prompt to something like:
        // a chibi young fire-type pokemon::1.8, a parrot, in a volcano environment, lava texture background, anime chibi drawing style, pastel background --niji --ar 3:2
        $segments = [
            'mdjrny-v4 style portrait of '.$this->cardType->value.'-type pokemon',
            'digital art',
            'sugimori',
            'chibi',
            'centerd',
            'full body',
        ];

        return Prompt::fromString(implode(', ', $segments));
    }

    private function buildSubjectDescription(): array
    {
        return [
            'a',
            ...$this->subjectAdjectives,
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
        $ambience = $cardType->getAmbience();
        $sizeAdjectives = $pokemonSize->getAdjectives();
        $rarityAdjectives = $pokemonRarity->getAdjectives();

        return new self(
            $cardType,
            $pokemonRarity,
            $creature->getName(),
            [
                $sizeAdjectives[array_rand($sizeAdjectives)],
                $rarityAdjectives[array_rand($rarityAdjectives)],
            ],
            $creature->getRandomDescriptionForCardType($cardType),
            $environments[array_rand($environments)],
            $ambience[array_rand($ambience)],
        );
    }
}
