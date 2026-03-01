<?php

namespace App\Controller;


use App\Dto\PokemonDto;
use App\Entity\Pokemon;
use App\Exception\PokemonAlreadyExistsException;
use App\Security\ApiKeyValidator;
use App\Service\Pokemon\PokemonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('pokemon')]
class PokemonController extends AbstractController
{
    public function __construct(
        private readonly ApiKeyValidator     $apiKeyValidator,
        private readonly SerializerInterface $serializer,
        private readonly PokemonService     $pokemonService
    ){}

    #[Route('/', methods: ['POST'])]
    public function createPokemon(Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        try {
            $ownPokemon = $this->serializer->deserialize($request->getContent(), PokemonDto::class, 'json');
            $id = $this->pokemonService->create($ownPokemon);
        } catch (ExceptionInterface $e) {
            return new JsonResponse([$e->getMessage()]);
        } catch (PokemonAlreadyExistsException $e) {
            return new JsonResponse([$e->getMessage()]);
        }

        return new JsonResponse($id);
    }

    #[Route('/{identifier}', methods: ['GET'])]
    public function getPokemon(string $identifier, Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        try {
            $pokemon = $this->pokemonService->getPokemon($identifier);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse([$e->getMessage()]);
        }

        $json = $this->serializer->serialize($pokemon, 'json');

        return new JsonResponse($json, 200, [], true);
    }


    #[Route('/{identifier}', methods: ['DELETE'])]
    public function deletePokemon(string $identifier, Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        try {
            $this->pokemonService->delete($identifier);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse([$e->getMessage()]);
        }

        return new JsonResponse(1);
    }

    #[Route('/{identifier}', methods: ['PUT'])]
    public function updatePokemon(string $identifier, Request $request): JsonResponse
    {
        $result = $this->apiKeyValidator->isValid($request);
        if ($result !== true) {
            return $result;
        }

        try {
            $pokemonDto = $this->serializer->deserialize($request->getContent(), PokemonDto::class, 'json');
            $pokemon = $this->pokemonService->update($identifier, $pokemonDto);
        } catch (ExceptionInterface $e) {
            return new JsonResponse([$e->getMessage()]);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse([$e->getMessage()]);
        }

        $json = $this->serializer->serialize($pokemon, 'json');

        return new JsonResponse($json, 200, [], true);
    }
}
