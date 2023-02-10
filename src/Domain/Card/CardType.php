<?php

namespace App\Domain\Card;

enum CardType: string
{
    case DARK = 'dark';
    case ELECTRIC = 'electric';
    case FIGHTING = 'fighting';
    case FIRE = 'fire';
    case GRASS = 'grass';
    case NORMAL = 'normal';
    case PSYCHIC = 'psychic';
    case STEEL = 'steel';
    case WATER = 'water';

    public function getEnvironments(): array
    {
        return match ($this) {
            CardType::NORMAL => [Environment::fromString('village'), Environment::fromString('field'), Environment::fromString('grassland')],
            CardType::FIRE => [Environment::fromString('volcano'), Environment::fromString('desert')],
            CardType::WATER => [Environment::fromString('ocean'), Environment::fromString('lake'), Environment::fromString('river')],
            CardType::GRASS => [Environment::fromString('forest'), Environment::fromString('jungle'), Environment::fromString('woods')],
            CardType::ELECTRIC => [Environment::fromString('mountain'), Environment::fromString('city'), Environment::fromString('thunderstorm')],
            CardType::PSYCHIC, CardType::DARK => [Environment::fromString('castle'), Environment::fromString('cave'), Environment::fromString('crypt')],
            CardType::FIGHTING => [Environment::fromString('arena'), Environment::fromString('ruins'), Environment::fromString('canyon')],
            CardType::STEEL => [Environment::fromString('mountain'), Environment::fromString('desert')],
        };
    }

    public function getAmbience(): array
    {
        return match ($this) {
            CardType::NORMAL => [
                'pastel colors',
                'bright lighting',
                'soft ambient light',
                'faded prismatic bokeh background',
                'silver galaxy background',
            ],
            CardType::FIRE => [
                'red and purple ambient lighting',
                'blue and red ambient lighting',
                'lava texture background',
                'orange galaxy background',
            ],
            CardType::WATER => [
                'teal and blue ambient lighting',
                'aurora background',
                'sparkling blue background',
                'gleaming bubble background',
                'sapphire blue galaxy background',
            ],
            CardType::GRASS => [
                'green and orange ambient lighting',
                'green and teal ambient lighting',
                'emerald bokeh lighting',
                'sunlight ray ambience',
                'emerald galaxy background',
            ],
            CardType::ELECTRIC => [
                'yellow and teal ambient lighting',
                'lightning background',
                'orange galaxy background',
            ],
            CardType::PSYCHIC => [
                'pink bokeh lighting',
                'violet shadows',
                'dreamy background',
                'galaxy background',
            ],
            CardType::FIGHTING => [
                'orange ambient lighting',
                'red and purple ambient lighting',
                'orange and blue ambient lighting',
                'galaxy background',
            ],
            CardType::STEEL => [
                'metallic ambient lighting',
                'grey ambient lighting',
                'grey shadows',
                'grey galaxy background',
            ],
            CardType::DARK => [
                'pink bokeh lighting',
                'violet shadows',
                'dreamy background',
                'dark galaxy background',
            ],
        };
    }

    public function getAdjectives(): array
    {
        return [
            'white',
            'dark',
            'golden',
            'regal',
            'ornate',
            'ancient',
            ...match ($this) {
                CardType::NORMAL => ['white', 'shiny', 'prismatic', 'opal', 'diamond'],
                CardType::FIRE => ['red and white', 'orange and black', 'fiery', 'ruby'],
                CardType::WATER => [
                    'blue and white',
                    'white and black',
                    'teal and navy',
                    'blue crystal',
                    'cyan glittering',
                    'sapphire',
                ],
                CardType::GRASS => [
                    'green and brown',
                    'white and green',
                    'stone',
                    'wooden',
                    'leafy',
                    'green runic',
                ],
                CardType::ELECTRIC => [
                    'yellow and teal',
                    'yellow and black',
                    'golden',
                    'lightning-charged',
                ],
                CardType::PSYCHIC, CardType::DARK => [
                    'amethyst',
                    'purple cosmic',
                    'galaxy-pattern',
                    'violet hypnotic',
                ],
                CardType::FIGHTING => ['red and black', 'rocky', 'stone', 'brown and grey'],
                CardType::STEEL => ['grey', 'metallic'],
            },
        ];
    }

    public function getColor(): string
    {
        return match ($this) {
            CardType::NORMAL => '#9EAAB4',
            CardType::FIRE => '#D63B39',
            CardType::WATER => '#12ABD8',
            CardType::GRASS => '#32A24C',
            CardType::ELECTRIC => '#CB922A',
            CardType::PSYCHIC => '#8C4F81',
            CardType::DARK => '#4A4D47',
            CardType::FIGHTING => '#CC4018',
            CardType::STEEL => '#A89A8D',
        };
    }
}
