<?php

namespace App\Api;

use App\Dto\PokemonDto;

interface ApiProviderInterface
{
    public function getPokemon(string $identifier);
}
