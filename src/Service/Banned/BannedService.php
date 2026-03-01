<?php

namespace App\Service\Banned;

use App\Dto\BannedPokemonDto;
use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BannedService
{
    public function __construct(
        private PokemonRepository $pokemonRepository,
    ){}

    public function create(BannedPokemonDto $bannedPokemonDto): int
    {
        $newPokemon = new Pokemon();
        $newPokemon->setName($bannedPokemonDto->name);
        $newPokemon->setIsBanned(1);

        $this->pokemonRepository->save($newPokemon);

        return $newPokemon->getId();
    }

    public function getAll(): array
    {
        return $this->pokemonRepository->getAllBannedPokemons();
    }

    public function delete(string $name): true
    {
        $findPokemon = $this->pokemonRepository->findOneBy(['name' => $name]);
        if (!$findPokemon) {
            throw new NotFoundHttpException('Pokemon with this name not exists.');
        }

        $this->pokemonRepository->delete($findPokemon);

        return true;
    }

    public function removeBannedPokemons(array $pokemonNames): array
    {

    }
}
