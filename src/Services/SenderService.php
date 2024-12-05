<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\SenderResponse;

class SenderService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $apiHash;

    public function __construct(HttpClientInterface $httpClient, string $apiKey, string $apiHash)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
    }

    /**
     * Get list of approved sender IDs
     */
    public function list(): SenderResponse
    {
        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
            ],
        ];

        $response = $this->httpClient->post('get-sender/json', [
            'json' => $payload,
        ]);

        return new SenderResponse($response->getBody(), $response->getStatusCode());
    }
} 