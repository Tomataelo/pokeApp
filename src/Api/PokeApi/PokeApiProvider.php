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
        $pokemonData = $this->apiClient->get('pokemon/'.$pokemonNames);



        return ;
    }
}
