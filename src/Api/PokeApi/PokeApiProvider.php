<?php

namespace App\Api\PokeApi;

use App\Api\ApiClientInterface;
use App\Api\ApiProviderInterface;
use App\Exception\ApiRequestException;
use Predis\Client;

readonly class PokeApiProvider implements ApiProviderInterface
{
    public function __construct(
        private ApiClientInterface $apiClient,
        private Client $cache,
    ) {}

    public function getPokemon(string $identifier): array|bool
    {
        try {

            $cached = $this->cache->get($identifier);
            if ($cached) {
                return json_decode($cached, true);
            }

            $pokemon = $this->apiClient->get('pokemon/' . $identifier);
            $this->cache->setex($identifier, $this->getSecondsUntilNoon(), json_encode($pokemon));

        } catch (ApiRequestException $e) {
            if ($e->getCode() === 404) {
                return false;
            }
        }

        return $pokemon;
    }

    private function getSecondsUntilNoon(): int
    {
        $now = new \DateTimeImmutable();
        $noon = new \DateTimeImmutable('today 12:00');

        if ($now > $noon) {
            $noon = new \DateTimeImmutable('tomorrow 12:00');
        }

        return $noon->getTimestamp() - $now->getTimestamp();
    }
}
