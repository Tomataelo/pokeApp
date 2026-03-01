<?php

namespace App\Api\PokeApi;

use App\Api\ApiClientInterface;
use App\Exception\ApiRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class PokeApiClient implements ApiClientInterface
{
    private Client $client;
    private const string API_BASE_URL = 'https://pokeapi.co/api/v2/';

    public function __construct(){
        $this->client = new Client([
            'base_uri' => self::API_BASE_URL,
        ]);
    }

    public function get(string $resource): array
    {
        try {
            $response = $this->client->get($resource);

            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            $this->handleApiError($e);
        }
    }

    private function handleResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    private function handleApiError(GuzzleException $e)
    {
        throw new ApiRequestException("HTTP Status Code: ".$e->getCode()." | Error: ".$e->getMessage(), $e->getCode());
    }
}
