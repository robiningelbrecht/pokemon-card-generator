<?php

namespace App\Domain\AI;

use OpenAI\Client;

class OpenAI
{
    public function __construct(
        private readonly Client $client
    ) {
    }

    public function createCompletion(
        Prompt $prompt,
    ): string {
        $response = $this->client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => (string) $prompt,
            'n' => 1,
            'max_tokens' => 256,
        ]);

        return trim($response->toArray()['choices'][0]['text']);
    }

    public function createChatCompletion(
        Prompt $prompt,
    ): string {
        $response = $this->client->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => (string) $prompt],
            ],
            'n' => 1,
            'max_tokens' => 256,
        ]);

        return trim($response->toArray()['choices'][0]['message']['content']);
    }
}
