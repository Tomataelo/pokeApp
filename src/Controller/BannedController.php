<?php

namespace App\Controller;

use App\Dto\BannedPokemonDto;
use App\Dto\PokemonDto;
use App\Security\ApiKeyValidator;
use App\Service\Banned\BannedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('banned')]
class BannedController extends AbstractController
{
    public function __construct(
        private readonly ApiKeyValidator     $apiKeyValidator,
        private readonly SerializerInterface $serializer,
        private readonly BannedService       $bannedService
    ){}

    #[Route('/', methods: ['POST'])]
    public function createBannedPokemon(Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        try {
            $bannedPokemon = $this->serializer->deserialize($request->getContent(), PokemonDto::class, 'json');
        } catch (ExceptionInterface $e) {
            return new JsonResponse([$e->getMessage()]);
        }

        $id = $this->bannedService->create($bannedPokemon);

        return new JsonResponse($id);
    }

    #[Route('/all', methods: ['GET'])]
    public function getAllBanned(Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        $allBannedPokemons = $this->bannedService->getAll();
        $serialized = $this->serializer->serialize($allBannedPokemons, 'json', [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
        ]);

        return new JsonResponse($serialized, 200, [], true);
    }

    #[Route('/{name}', methods: ['DELETE'])]
    public function deleteBannedPokemon(string $name, Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        try {
            $this->bannedService->delete($name);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse([$e->getMessage()]);
        }

        return new JsonResponse(1);
    }
}
