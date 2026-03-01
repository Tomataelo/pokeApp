<?php

namespace App\Api;

interface ApiProviderInterface
{
    public function getPokemonsInfo(string $pokemonName): array;
}
