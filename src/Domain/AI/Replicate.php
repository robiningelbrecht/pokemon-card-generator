<?php

namespace App\Domain\AI;

use App\Infrastructure\Serialization\Json;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Replicate
{
    public function __construct(
        private readonly Client $client,
        private readonly ReplicateApiKey $replicateApiKey,
    ) {
    }

    private function request(
        string $path,
        string $method = 'GET',
        array $options = []): array
    {
        $options = array_merge([
            'base_uri' => 'https://api.replicate.com/',
            RequestOptions::HEADERS => [
                'Authorization' => 'Token '.$this->replicateApiKey,
                'Content-Type' => 'application/json',
            ],
        ], $options);
        $response = $this->client->request($method, $path, $options);

        return Json::decode($response->getBody()->getContents());
    }

    public function predict(Prompt $prompt): array
    {
        $options = [
            RequestOptions::JSON => [
                'version' => '9936c2001faa2194a261c01381f90e65261879985476014a0a37a334593a05eb',
                'input' => [
                    'prompt' => (string) $prompt,
                    'num_outputs' => 1,
                    'width' => 768,
                    'height' => 512,
                ],
            ],
        ];

        return $this->request('v1/predictions', 'POST', $options);
    }

    public function getPrediction(string $id): array
    {
        return $this->request('v1/predictions/'.$id);
    }
}
