<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalApiService
{
    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function makeGetRequest(string $url): array
    {
        $apiResponse = $this->client->request('GET', $url);

        if (200 === $apiResponse->getStatusCode()) {
            return $apiResponse->toArray();
        } else {
            return [];
        }
    }
}
