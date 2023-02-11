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
| Generate a series that evolve from one another  |         ❌        |      ✅      |
| Fully generate a card with one command          |         ✅        |      ❌      |
| Gallery with overview of generated cards        |         ✅        |      ❌      |

## Installation

```bash
git clone git@github.com:robiningelbrecht/pokemon-card-generator.git
# Build docker containers
docker-compose up -d --build
# Install dependencies
docker-compose run --rm php-cli composer install
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
docker-compose run --rm php-cli bin/console app:card:generate
```

Your CLI should output something along the lines of

<img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/cli-output.png" alt="CLI output">

You'll be able to view your card and all information used to generate it, 
by navigating to the card gallery page: `http://localhost:8080/`.

<img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/gallery.png" alt="Gallery">

### CLI command options

```
-t, --cardType[=CARDTYPE]              The card type you want to generate, omit to use a random one. Valid options are dark, electric, fighting, fire, grass, normal, psychic, steel, water
-r, --rarity[=RARITY]                  The rarity of the Pokémon you want to generate, omit to use a random one. Valid options are common, uncommon, rare
-s, --size[=SIZE]                      The size of the Pokémon you want to generate, omit to use a random one. Valid options are xl, l, m, s, xs
-c, --creature[=CREATURE]              The creature the Pokémon needs to look like (e.g. monkey, dragon, etc.). Omit to to use a random one
-num, --numberOfCards[=NUMBEROFCARDS]  The number of cards to generate. Number between 1 and 10 [default: 1]
-h, --help                             Display help for the given command. When no command is given display help for the list command
```

## Examples

|                                                                                                                                                     |                                             |                                               |                                           |
|-----------------------------------------------------------------------------------------------------------------------------------------------------| ------------------------------------------- | --------------------------------------------- | ----------------------------------------- |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-1b5363f3-edb6-4afe-b945-14a81f138a3e.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-1e76bc80-21ea-4be0-8f5b-79792f67a58d.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-1f180796-434f-47b5-9646-0492ca8c8993.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-3bcbe7a4-2892-4f3d-91de-6be59ce50108.svg)           |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-5ae666fe-637e-4c4e-a04f-73fc25862755.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-8b682277-68b8-4a80-92a8-7c13a567dfbb.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-17c939ea-cafc-44bd-845f-f321a3f3a335.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-29b56e79-e96c-47a4-9d24-0ddd01bd93de.svg)           |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-67e1d9a4-d2d5-4d05-921f-7bebdfee7a26.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-967fd5d0-7d98-4cec-8b94-35a9d429c815.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-2382f3af-c7c0-4941-937f-8726c846b9e5.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-36213ec6-c72e-4888-ac47-ec553f89ad77.svg)           |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-9605602b-bc55-4991-88dc-6ca55c24f7d4.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-20568966-93cc-4845-abef-3e7993d5e19b.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-50296507-6a6c-4256-962e-bdcdcef45e74.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-65733654-77e1-4f76-88d8-af8cc364acd7.svg)           |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-a9577479-20db-4704-b1af-ecff6cdc5488.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-ab472b6b-edb6-443b-8fe8-8cb54b9b5276.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-b4a94b61-133b-4b18-8d16-f583c4087ade.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-c38adca2-33cb-4a42-9cca-c9202d8aa7f6.svg)           |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-d4ee36c4-e216-4f28-b215-4f6e8e74bd02.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-d212d836-5218-493a-b1f1-0ed68a1ee8ca.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-db1f015d-a663-4895-a57e-e6fafefc4f0f.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-e554bcb7-c17b-4741-bff6-2a65f1142a9e.svg)           |
| ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-e613cbbf-0f9c-4645-9e8e-9a14525eeece.svg) | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-f89b31fe-8ed2-4c84-a019-58d6e6903a32.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-f1298674-bafc-467e-b0c6-7173db0364c7.svg)         | ![](https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/examples/card-fbe16d11-e87e-4953-8927-e399ade023d1.svg)           |

## Acknowledgements

Thanks to [TheDuckTamerBlanks](https://www.deviantart.com/katarawaterbender) for the blank card templates.
