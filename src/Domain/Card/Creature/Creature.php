<?php

namespace App\Domain\Card\Creature;

use App\Domain\Card\CardType;
use App\Infrastructure\ValueObject\String\Description;
use App\Infrastructure\ValueObject\String\Name;

class Creature
{
    private function __construct(
        private readonly Name $name,
        private readonly array $creatureAttributes)
    {
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getRandomDescriptionForCardType(CardType $cardType): ?Description
    {
        if ($this->creatureAttributes) {
            /** @var \App\Domain\Card\Creature\CreatureAttribute $randomAttribute */
            $randomAttribute = $this->creatureAttributes[array_rand($this->creatureAttributes)];
            $adjectives = $cardType->getAdjectives();

            $randomAttribute->withAdjective($adjectives[array_rand($adjectives)]);

            return Description::fromString($randomAttribute);
        }

        return null;
    }

    public static function fromNameAndAttributes(
        Name $name,
        CreatureAttribute ...$creatureAttributes
    ): self {
        return new self($name, $creatureAttributes);
    }

    public static function swan(): self
    {
        return self::fromNameAndAttributes(
            Name::fromString('swan'),
            ...CreatureAttribute::forABird()
        );
    }

    public static function serpent(): self
    {
        return self::fromNameAndAttributes(
            Name::fromString('serpent'),
            ...CreatureAttribute::forANoHandReptile()
        );
    }
}
