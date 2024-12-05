<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\AccountResponse;

class AccountService
{
    private $httpClient;
    private $apiKey;
    private $apiHash;

    public function __construct(HttpClientInterface $httpClient, string $apiKey, string $apiHash)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
    }

    public function balance(): AccountResponse
    {
        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash
                ]
            ]
        ];

        $response = $this->httpClient->post('get-balance/json', [
            'json' => $payload,
        ]);

        return new AccountResponse($response->getBody(), $response->getStatusCode());
    }
}