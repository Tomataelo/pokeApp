<?php

namespace App\Api\PokeApi;

use App\Api\ApiClientInterface;
use App\Api\ApiProviderInterface;

readonly class PokeApiProvider implements ApiProviderInterface
{
    public function __construct(
        private ApiClientInterface $apiClient
    ) {}

    public function getPokemonsInfo(array $pokemonNames): array
    {
        $result = [];
        foreach ($pokemonNames as $pokemonName) {
            $response = $this->apiClient->get('pokemon/'.$pokemonName);

            $result[] = [
                'name' => $response['name'],
                'height' => $response['height'],
                'weight' => $response['weight'],
            ];
        }

        return $result;
    }
}
