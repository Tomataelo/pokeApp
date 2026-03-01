<?php

namespace App\Api\PokeApi;

use App\Api\ApiClientInterface;
use App\Api\ApiProviderInterface;
use App\Exception\ApiRequestException;


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
                'own' => 0
            ];
        }

        return $result;
    }

    public function getPokemonByName(string $identifier): bool
    {
        try {
            $this->apiClient->get('pokemon/' . $identifier);
        } catch (ApiRequestException $e) {
            if ($e->getCode() === 404) {
                return false;
            }
        }

        return true;
    }
}
