<?php

namespace App\Domain\Card\Creature;

class CreatureAttribute implements \Stringable
{
    private ?string $adjective = null;

    private function __construct(
        private readonly string $relation,
        private readonly string $detail,
        private readonly ?string $quantifier = null
    ) {
    }

    public function withAdjective(string $adjective): self
    {
        $this->adjective = $adjective;

        return $this;
    }

    public function __toString(): string
    {
        return implode(' ', array_filter([$this->relation, $this->quantifier, $this->adjective, $this->detail]));
    }

    public static function holding($detail, $quantifier = null): self
    {
        return new self('holding', $detail, $quantifier);
    }

    public static function wearing($detail, $quantifier = null): self
    {
        return new self('wearing', $detail, $quantifier);
    }

    public static function with($detail, $quantifier = null): self
    {
        return new self('with', $detail, $quantifier);
    }

    public static function withATail(): self
    {
        return self::with('tail', 'a');
    }

    public static function withSkin(): self
    {
        return self::with('skin');
    }

    public static function withClaws(): self
    {
        return self::with('claws');
    }

    public static function withFur(): self
    {
        return self::with('fur');
    }

    public static function withHooves(): self
    {
        return self::with('hooves');
    }

    public static function withAntlers(): self
    {
        return self::with('antlers');
    }

    public static function withAShell(): self
    {
        return self::with('shell', 'a');
    }

    public static function withACristalCore(): self
    {
        return self::with('crystal core', 'a');
    }

    public static function withScales(): self
    {
        return self::with('scales');
    }

    public static function wearingArmor(): self
    {
        return self::wearing('armor');
    }

    public static function holdableWeapons(): array
    {
        return [
            CreatureAttribute::holding('sword', 'a'),
            CreatureAttribute::holding('bow', 'a'),
            CreatureAttribute::holding('staff', 'a'),
            CreatureAttribute::holding('shield', 'a'),
            CreatureAttribute::holding('axe', 'an'),
            CreatureAttribute::holding('dagger', 'a'),
            CreatureAttribute::holding('spear', 'a'),
            CreatureAttribute::holding('mace', 'a'),
            CreatureAttribute::holding('hammer', 'a'),
            CreatureAttribute::holding('club', 'a'),
            CreatureAttribute::holding('lance', 'a'),
            CreatureAttribute::holding('whip', 'a'),
            CreatureAttribute::holding('glaive', 'a'),
        ];
    }

    public static function headWearables(): array
    {
        return [
            self::wearing('mask', 'a'),
            self::wearing('crown', 'a'),
            self::wearing('crystal headband', 'a'),
        ];
    }

    public static function allWearables(): array
    {
        return [
            self::wearing('bracers'),
            self::wearing('gemstones'),
            self::wearingArmor(),
            ...self::headWearables(),
        ];
    }

    public static function forANoHandReptile(): array
    {
        return [self::withATail(), self::withScales(), ...self::allWearables()];
    }

    public static function forABird(): array
    {
        return [self::withATail(), self::with('feathers'), self::with('beak', 'a'), ...self::allWearables()];
    }

    public static function forALizard(): array
    {
        return [self::withATail(), self::withScales(), ...self::allWearables(), ...self::holdableWeapons()];
    }

    public static function forAnInsect(): array
    {
        return [self::withACristalCore(), self::with('wings')];
    }
}
