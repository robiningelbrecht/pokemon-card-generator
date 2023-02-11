<?php

namespace App\Domain\Card\Creature;

use App\Domain\Card\CardType;
use App\Infrastructure\ValueObject\String\Name;

class CreaturePool
{
    /**
     * @return \App\Domain\Card\Creature\Creature[]
     */
    public static function landMammals(): array
    {
        return [
            Creature::fromNameAndAttributes(
                Name::fromString('wolf'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()),
            Creature::fromNameAndAttributes(
                Name::fromString('bear'),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('monkey'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables(),
                ...CreatureAttribute::holdableWeapons()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('gorilla'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables(),
                ...CreatureAttribute::holdableWeapons()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('bull'),
                CreatureAttribute::with('horns'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::withSkin(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('bison'),
                CreatureAttribute::with('horns'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::withSkin(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('elephant'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::with('tusks'),
                CreatureAttribute::withSkin(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('boar'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::with('tusks'),
                CreatureAttribute::withSkin(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('tiger'),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('lynx'),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('lion'),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('rabbit'),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables(),
                ...CreatureAttribute::holdableWeapons()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('fox'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('deer'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::withAntlers(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('ibex'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::withAntlers(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('goat'),
                CreatureAttribute::withHooves(),
                CreatureAttribute::withAntlers(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('horse'),
                CreatureAttribute::withHooves(),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('cat'),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withFur(),
                ...CreatureAttribute::allWearables()
            ),
        ];
    }

    /**
     * @return \App\Domain\Card\Creature\Creature[]
     */
    public static function marineCreatures(): array
    {
        return [
            Creature::fromNameAndAttributes(
                Name::fromString('reptile'),
                CreatureAttribute::withATail(), CreatureAttribute::withSkin(), ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('clam'),
                CreatureAttribute::withAShell(), CreatureAttribute::withACristalCore(),
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('penguin'),
                CreatureAttribute::withATail(), CreatureAttribute::withFur(), ...CreatureAttribute::allWearables(), ...CreatureAttribute::holdableWeapons()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('orca'),
                CreatureAttribute::withATail(), CreatureAttribute::withSkin(), CreatureAttribute::wearingArmor()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('shark'),
                CreatureAttribute::withATail(), CreatureAttribute::with('fins'), CreatureAttribute::wearingArmor()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('squid'),
                CreatureAttribute::withACristalCore(), CreatureAttribute::with('tentacles'),
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('crustacean'),
                CreatureAttribute::withClaws(),
                CreatureAttribute::withAShell(),
                CreatureAttribute::wearingArmor(),
                CreatureAttribute::withACristalCore(),
                CreatureAttribute::with('carapace'),
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('tortoise'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withAShell(),
                CreatureAttribute::with('carapace'),
                ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('sea-horse'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withAShell(), ...CreatureAttribute::allWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('sea-snake'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withScales(),
                CreatureAttribute::wearingArmor(),
                ...CreatureAttribute::headWearables()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('fish'),
                CreatureAttribute::withATail(),
                CreatureAttribute::withScales(),
                CreatureAttribute::wearingArmor()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('octopus'),
                CreatureAttribute::with('tentacles'),
                CreatureAttribute::wearingArmor()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('crocodile'),
                ...CreatureAttribute::forANoHandReptile()
            ),
            Creature::serpent(),
            Creature::swan(),
        ];
    }

    /**
     * @return \App\Domain\Card\Creature\Creature[]
     */
    public static function birds(): array
    {
        return [
            Creature::fromNameAndAttributes(Name::fromString('bird'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('parrot'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('owl'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('eagle'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('hawk'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('falcon'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('crow'), ...CreatureAttribute::forABird()),
            Creature::fromNameAndAttributes(Name::fromString('ostrich'), ...CreatureAttribute::forABird()),
            Creature::swan(),
        ];
    }

    /**
     * @return \App\Domain\Card\Creature\Creature[]
     */
    public static function reptiles(): array
    {
        return [
            Creature::fromNameAndAttributes(
                Name::fromString('dragon'),
                CreatureAttribute::withACristalCore(),
                ...CreatureAttribute::forALizard()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('lizard'),
                ...CreatureAttribute::forALizard()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('chameleon'),
                ...CreatureAttribute::forALizard()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('frilled-lizard'),
                ...CreatureAttribute::forANoHandReptile()
            ),
            Creature::fromNameAndAttributes(
                Name::fromString('gecko'),
                ...CreatureAttribute::forALizard()
            ),
            Creature::serpent(),
        ];
    }

    /**
     * @return \App\Domain\Card\Creature\Creature[]
     */
    public static function insects(): array
    {
        return [
            Creature::fromNameAndAttributes(Name::fromString('butterfly'), ...CreatureAttribute::forAnInsect()),
            Creature::fromNameAndAttributes(Name::fromString('mantis'), ...CreatureAttribute::forAnInsect()),
            Creature::fromNameAndAttributes(Name::fromString('beetle'), ...CreatureAttribute::forAnInsect()),
            Creature::fromNameAndAttributes(Name::fromString('ladybug'), ...CreatureAttribute::forAnInsect()),
            Creature::fromNameAndAttributes(Name::fromString('dragonfly'), ...CreatureAttribute::forAnInsect()),
            Creature::fromNameAndAttributes(Name::fromString('spider'), ...CreatureAttribute::forAnInsect()),
            Creature::fromNameAndAttributes(Name::fromString('scorpion'), ...CreatureAttribute::forAnInsect()),
        ];
    }

    public static function randomByCardType(CardType $cardType): Creature
    {
        $creatures = match ($cardType) {
            CardType::NORMAL => [...self::birds(), ...self::landMammals()],
            CardType::FIRE, CardType::STEEL => [...self::landMammals(), ...self::reptiles()],
            CardType::WATER => [...self::marineCreatures(), ...self::reptiles()],
            CardType::GRASS => [...self::insects(), ...self::reptiles(), ...self::landMammals()],
            CardType::ELECTRIC => [...self::landMammals(), ...self::reptiles(), ...self::birds()],
            CardType::PSYCHIC => [...self::insects(), ...self::landMammals(), ...self::reptiles(), ...self::birds()],
            CardType::FIGHTING, CardType::DARK => [...self::landMammals(), ...self::insects(), ...self::reptiles()],
        };

        return $creatures[array_rand($creatures)];
    }

    public static function matchBySubject(Name $subject): Creature
    {
        /** @var \App\Domain\Card\Creature\Creature $creature */
        foreach ([...self::landMammals(), ...self::marineCreatures(), ...self::birds(), ...self::reptiles(), ...self::insects()] as $creature) {
            if ($creature->getName() == strtolower($subject)) {
                return $creature;
            }
        }

        return Creature::fromNameAndAttributes($subject);
    }
}
