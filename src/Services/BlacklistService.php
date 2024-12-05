<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\BlacklistResponse;
use IletiMerkezi\Responses\Response;

class BlacklistService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $apiHash;
    private ?string $lastStart = null;
    private ?string $lastEnd = null;
    private ?int $lastPage = null;

    public function __construct(HttpClientInterface $httpClient, string $apiKey, string $apiHash)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
    }

    public function list(?string $start = null, ?string $end = null, int $page = 1): BlacklistResponse
    {
        $this->lastStart = $start;
        $this->lastEnd = $end;
        $this->lastPage = $page;

        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
                'blacklist' => [
                    'page' => $page,
                    'rowCount' => 1000,
                ],
            ],
        ];

        if ($start || $end) {
            $payload['request']['blacklist']['filter'] = [
                'start' => $start,
                'end' => $end,
            ];
        }

        $response = $this->httpClient->post('get-blacklist/json', [
            'json' => $payload,
        ]);

        return new BlacklistResponse($response->getBody(), $response->getStatusCode(), $page);
    }

    public function next(): BlacklistResponse
    {
        if ($this->lastPage === null) {
            throw new \LogicException('No previous request made');
        }

        return $this->list($this->lastStart, $this->lastEnd, $this->lastPage + 1);
    }

    public function create(string $number): Response
    {
        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
                'blacklist' => [
                    'number' => $number,
                ],
            ],
        ];

        $response = $this->httpClient->post('add-blacklist/json', [
            'json' => $payload,
        ]);

        return new Response($response->getBody(), $response->getStatusCode());
    }

    public function delete(string $number): Response
    {
        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
                'blacklist' => [
                    'number' => $number,
                ],
            ],
        ];

        $response = $this->httpClient->post('delete-blacklist/json', [
            'json' => $payload,
        ]);

        return new Response($response->getBody(), $response->getStatusCode());
    }
} 