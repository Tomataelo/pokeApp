<?php

namespace App\Controller;

use App\Service\Banned\BannedService;
use App\Service\InfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('info')]
class InfoController extends AbstractController
{
    public function __construct(
        private readonly InfoService         $infoService,
        private readonly SerializerInterface $serializer,
        private readonly BannedService       $bannedService
    ) {}

    #[Route('/{pokemonNames}', methods: ['GET'])]
    public function info(string $pokemonNames): JsonResponse
    {
        $pokemonNames = $this->bannedService->removeBannedPokemons(explode(',', $pokemonNames));
        $serialized = $this->serializer->serialize($this->infoService->getPokemonsInfo($pokemonNames), 'json');

        return new JsonResponse($serialized, 200, [], true);
    }
}
