<h1 align="center">Pokemon card AI generator</h1>

<p align="center">
  <img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/banner.png" alt="Banner">
</p>

---

This PHP script uses AI to generate new, random Pokemon cards by using 
 - GPT to generate a name and description
 - Stable Diffusion to create a visual
 - [PokeApi](https://pokeapi.co/) for accurate moves sets and element types

First of all, thanks to [Jack](https://github.com/pixegami) for the amazing work 
on the [Python equivalent](https://github.com/pixegami/pokemon-card-generator) of
this repository. I was inspired by his creativity to generate Pokémon cards out of thin air, 
although I came up with a slightly different approach.

### Comparison

|                                                 |     This repo    | Jack's repo |
|-------------------------------------------------|:----------------:|:-----------:|
| AI to generate name and description             |      OpenAI      |    OpenAI   |
| AI to generate visual                           | Stable Difussion |  Midjourney |
| Generate multiple cards at once                 |         ✅        |      ✅      |
| Generate cards of a specific element            |         ✅        |      ✅      |
| Generate cards of a specific creature           |         ✅        |      ✅      |
| Generate a series that evolve from one another* |         ❌        |      ✅      |
| Fully generate a card with one command          |         ✅        |      ❌      |
| Gallery with overview of generated cards        |         ✅        |      ❌      |

<sub>* Planning on adding this feature</sub>

## Installation

```bash
> git clone git@github.com:robiningelbrecht/pokemon-card-generator.git
# Build docker containers
> docker-compose up -d --build
# Install dependencies
> docker-compose run --rm php-cli composer install
```

## Configuration

For this script to work you'll need two different API keys

### OpenAI

* Set up an OpenAI account by navigating to https://beta.openai.com/signup
* Create an API key on https://platform.openai.com/account/api-keys

### Replicate.com

* Set up a Replicate account by navigating to https://replicate.com/signin
* Copy your API key from https://replicate.com/account

The model used for generating visuals is [OpenJourney](https://replicate.com/prompthero/openjourney).
It's a Stable Diffusion model fine-tuned on Midjourney v4 images.

<sub>Both accounts have free tiers at first, but after a while you will need to enter
credit card details. It'll cost you around 1-2 cents to generate a card.</sub>

### .env

Copy `.env.dist` and rename to `.env`, it should at least contain following info:

```dotenv
OPEN_AI_API_KEY=your-open-ai-api-key
REPLICATE_API_KEY=your-replicate-api-key
```

## Generate your first card

At this point, you should be locked and loaded to generate your first Pokémon card by running

```bash
> docker-compose run --rm php-cli bin/console app:card:generate
```

Your CLI should output something along the lines of

<img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/cli-output.png" alt="CLI output">

You'll be able to view your card and all information used to generate it, 
by navigating to the card gallery page: `http://localhost:8080/`.

<img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/example-generated-card.png" alt="Example">

### CLI command options

```
-t, --cardType[=CARDTYPE]              The card type you want to generate, omit to use a random one. Valid options are dark, electric, fighting, fire, grass, normal, psychic, steel, water
-r, --rarity[=RARITY]                  The rarity of the Pokémon you want to generate, omit to use a random one. Valid options are common, uncommon, rare
-s, --size[=SIZE]                      The size of the Pokémon you want to generate, omit to use a random one. Valid options are xl, l, m, s, xs
-c, --creature[=CREATURE]              The creature the Pokémon needs to look like (e.g. monkey, dragon, etc.). Omit to to use a random one
-x, --numberOfCards[=NUMBEROFCARDS]    The number of cards to generate. Number between 1 and 10 [default: 1]
-h, --help                             Display help for the given command. When no command is given display help for the list command
```

## Examples

There's an example gallery available on https://pokemon-card-generator.robiningelbrecht.be/

<img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/gallery-example.png" alt="Gallery">

## Acknowledgements

Thanks to [TheDuckTamerBlanks](https://www.deviantart.com/katarawaterbender) for the blank card templates.
