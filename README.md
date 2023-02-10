<h1 align="center">Pokemon card generator</h1>

<p align="center">
  <img src="https://github.com/robiningelbrecht/pokemon-card-generator/raw/master/readme/banner.png" alt="Banner">
</p>

---

This PHP script uses AI to generate new, random Pokemon cards by using 
 - GPT to generates names and descriptions
 - Stable Diffusion to create a visual

First of all, thanks to [Jack](https://github.com/pixegami) for the amazing work 
on the [Python equivalent](https://github.com/pixegami/pokemon-card-generator) of
this repository. I came up with the idea because of him, although I came up with a
slightly different approach.

### Comparison

|                                                |     This repo    | Jack's repo |
|------------------------------------------------|:----------------:|:-----------:|
| AI to generate text                            |      OpenAI      |    OpenAI   |
| AI to generate visual                          | Stable Difussion |  Midjourney |
| Generate multiple cards at once                |         ❌        |      ✅      |
| Generate cards of a specific element           |         ✅        |      ✅      |
| Generate cards of a specific creature*         |         ❌        |      ✅      |
| Generate a series that evolve from one another |         ❌        |      ✅      |
| Fully generate a card with one command         |         ✅        |      ❌      |
| Gallery with overview of generated cards       |         ✅        |      ❌      |

*not until Midjourney opens up a public API 


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

* Set up an OpenAI account bynavigating to https://beta.openai.com/signup
* Create an API key on https://platform.openai.com/account/api-keys

### Replicate.com

* Set up a Replicate account by navigating to https://replicate.com/signin
* Copy your API key from https://replicate.com/account

The model used for generating visuals is [OpenJourney](https://replicate.com/prompthero/openjourney).
It's a Stable Diffusion model fine-tuned on Midjourney v4 images.

### .env

Copy `.env.dist` and rename to `.env`, it should at least contain following info:

```dotenv
OPEN_AI_API_KEY=your-open-ai-api-key
REPLICATE_API_KEY=your-replicate-api-key
```

## Generate your first card

- Commands
- Uses PokeAPi for moves
- show CLI output
- Link to gallery page


## Acknowledgements

Thanks to [TheDuckTamerBlanks](https://www.deviantart.com/katarawaterbender) for the blank card templates.
