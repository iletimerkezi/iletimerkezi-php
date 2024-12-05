<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\SummaryResponse;

class SummaryService
{
    private $httpClient;
    private $apiKey;
    private $apiHash;
    private $lastStart;
    private $lastEnd;
    private $lastPage;

    public function __construct(HttpClientInterface $httpClient, string $apiKey, string $apiHash)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
    }

    public function list(string $start, string $end, int $page = 1): SummaryResponse
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
                'filter' => [
                    'start' => $start,
                    'end' => $end
                ],
                'page' => $page
            ],
        ];

        $response = $this->httpClient->post('get-reports/json', [
            'json' => $payload,
        ]);

        return new SummaryResponse($response->getBody(), $response->getStatusCode(), $page);
    }

    public function next(): SummaryResponse
    {
        if (!$this->lastStart || !$this->lastEnd || !$this->lastPage) {
            throw new \RuntimeException('No previous report request found. Call getReport() first.');
        }

        return $this->list($this->lastStart, $this->lastEnd, $this->lastPage + 1);
    }
}