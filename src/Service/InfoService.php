<?php

namespace App\Service;

use App\Api\ApiProviderInterface;
use App\Service\Pokemon\PokemonService;

readonly class InfoService
{
    public function __construct(
        private ApiProviderInterface $apiProvider,
        private PokemonService $pokemonService
    ) {}

    public function getPokemonsInfo(array $pokemonNames): array
    {
        $result = [];
        foreach ($pokemonNames as $pokemonName) {
            $ownPokemon = $this->pokemonService->isPokemonExistInDb($pokemonName);
            if ($ownPokemon) {
                $result[] = [
                    'name' => $ownPokemon->getName(),
                    'height' => $ownPokemon->getHeight(),
                    'weight' => $ownPokemon->getWeight(),
                    'own' => 1
                ];

                continue;
            }

            $response = $this->apiProvider->getPokemon($pokemonName);
            $result[] = [
                'name' => $response['name'],
                'height' => $response['height'],
                'weight' => $response['weight'],
                'own' => 0
            ];
        }

        return $result;
    }
}
