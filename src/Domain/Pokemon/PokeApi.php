<?php

namespace App\Domain\Pokemon;

use App\Infrastructure\Serialization\Json;
use GuzzleHttp\Client;

class PokeApi
{
    public function __construct(
        private readonly Client $client
    ) {
    }

    private function request(
        string $path,
        string $method = 'GET',
        array $options = []): array
    {
        $options = array_merge([
            'base_uri' => 'https://pokeapi.co/',
        ], $options);
        $response = $this->client->request($method, $path, $options);

        return Json::decode($response->getBody()->getContents());
    }

    public function getMove(int $id): array
    {
        return $this->request('api/v2/move/'.$id);
    }

    public function getType(int $id): array
    {
        return $this->request('api/v2/type/'.$id);
    }
}
