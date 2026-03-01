<?php

namespace App\Controller;

use App\Api\ApiProviderInterface;
use App\Service\Banned\BannedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('info')]
class InfoController extends AbstractController
{
    public function __construct(
        private readonly ApiProviderInterface $apiProvider,
        private readonly SerializerInterface $serializer,
        private readonly BannedService $bannedService
    ) {}

    #[Route('/{pokemonNames}', methods: ['GET'])]
    public function info(string $pokemonNames): JsonResponse
    {
        $pokemonNames = $this->bannedService->removeBannedPokemons(explode(',', $pokemonNames));
        $serialized = $this->serializer->serialize($this->apiProvider->getPokemonsInfo($pokemonNames), 'json');

        return new JsonResponse($serialized, 200, [], true);
    }
}
