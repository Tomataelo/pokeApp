<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class ApiKeyValidator
{
    public function __construct(
        private string $apiKey
    ){}

    public function isValid(Request $request): JsonResponse|bool
    {
        $key = $request->headers->get('X-SUPER-SECRET-KEY');

        if (!$key) {
            return new JsonResponse(['error' => 'Missing X-SUPER-SECRET-KEY header.'], 401);
        }

        if ($key !== $this->apiKey) {
            return new JsonResponse(['error' => 'Invalid API key.'], 403);
        }

        return true;
    }
}
