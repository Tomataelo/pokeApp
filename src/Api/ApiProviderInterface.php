<?php

namespace App\Api;

interface ApiProviderInterface
{
    public function getPokemonsInfo(array $pokemonNames): array;
}
