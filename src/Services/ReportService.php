<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\ReportResponse;

class ReportService
{
    private $httpClient;
    private $apiKey;
    private $apiHash;
    private $lastOrderId;
    private $lastPage;

    public function __construct(HttpClientInterface $httpClient, string $apiKey, string $apiHash)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
    }

    public function get(int $orderId, int $page = 1): ReportResponse
    {
        $this->lastOrderId = $orderId;
        $this->lastPage = $page;

        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
                'order' => [
                    'id' => $orderId,
                    'page' => $page,
                    'rowCount' => 1000,
                ],
            ],
        ];

        $response = $this->httpClient->post('get-report/json', [
            'json' => $payload,
        ]);

        return new ReportResponse($response->getBody(), $response->getStatusCode(), $page);
    }

    public function next(): ReportResponse
    {
        if (!$this->lastOrderId || !$this->lastPage) {
            throw new \RuntimeException('No previous report request found. Call getReport() first.');
        }

        return $this->get($this->lastOrderId, $this->lastPage + 1);
    }
}