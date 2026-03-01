<?php

namespace App\Api;

use App\Dto\PokemonDto;

interface ApiProviderInterface
{
    public function getPokemonsInfo(array $pokemonNames): array;

    public function getPokemonByName(string $identifier);
}
