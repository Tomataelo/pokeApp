<?php

namespace App\Service\Pokemon;

use App\Api\PokeApi\PokeApiProvider;
use App\Dto\PokemonDto;
use App\Entity\Pokemon;
use App\Exception\PokemonAlreadyExistsException;
use App\Repository\PokemonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PokemonService
{

    public function __construct(
        private PokeApiProvider $apiProvider,
        private PokemonRepository $pokemonRepository
    ){}

    public function create(PokemonDto $pokemonDto): int
    {
        $isExist = $this->isPokemonExist($pokemonDto->getName());
        if ($isExist) {
            throw new PokemonAlreadyExistsException();
        }

        $newPokemon = new Pokemon();
        $newPokemon->setName($pokemonDto->getName());
        $newPokemon->setHeight($pokemonDto->getHeight());
        $newPokemon->setWeight($pokemonDto->getWeight());
        $newPokemon->setIsBanned(0);

        $this->pokemonRepository->save($newPokemon);

        return $newPokemon->getId();
    }

    private function isPokemonExist(string $name): bool
    {
        $isExist = $this->pokemonRepository->isPokemonExist($name);
        if ($isExist) {
            return true;
        }

        $isExistInPokeApi = $this->apiProvider->getPokemonByName($name);
        if ($isExistInPokeApi) {
            return true;
        }

        return false;
    }

    public function getPokemon(string $identifier): ?Pokemon
    {
        return $this->getByIdentifier($identifier);
    }

    public function delete(string $identifier): void
    {
        $this->pokemonRepository->delete($this->getByIdentifier($identifier));
    }

    public function update(string $identifier, PokemonDto $pokemonDto): Pokemon
    {
        $pokemon = $this->getByIdentifier($identifier);

        if ($pokemonDto->getName() !== null && $pokemonDto->getName() !== $pokemon->getName()) {
            $pokemon->setName($pokemonDto->getName());
        }
        if ($pokemonDto->getHeight() !== null && $pokemonDto->getHeight() !== $pokemon->getHeight()) {
            $pokemon->setHeight($pokemonDto->getHeight());
        }
        if ($pokemonDto->getWeight() !== null && $pokemonDto->getWeight() !== $pokemon->getWeight()) {
            $pokemon->setWeight($pokemonDto->getWeight());
        }

        $this->pokemonRepository->save($pokemon);

        return $pokemon;
    }

    private function getByIdentifier(string $identifier): ?Pokemon
    {
        if (is_numeric($identifier)) {
            $pokemon = $this->pokemonRepository->find((int) $identifier);
        } else {
            $pokemon = $this->pokemonRepository->findOneBy(['name' => strtolower($identifier), 'is_banned' => 0]);
        }

        if (!$pokemon) {
            throw new NotFoundHttpException("Pokemon with identifier $identifier could not be found.");
        }

        return $pokemon;
    }
}
