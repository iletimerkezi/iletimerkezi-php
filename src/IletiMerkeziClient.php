<?php

namespace IletiMerkezi;

use RuntimeException;
use IletiMerkezi\Services\SmsService;
use IletiMerkezi\Services\ReportService;
use IletiMerkezi\Services\SummaryService;
use IletiMerkezi\Services\SenderService;
use IletiMerkezi\Services\BlacklistService;
use IletiMerkezi\Services\AccountService;
use IletiMerkezi\Services\WebhookService;
use IletiMerkezi\Http\CurlHttpClient;
use IletiMerkezi\Http\GuzzleHttpClient;
use IletiMerkezi\Http\HttpClientInterface;

class IletiMerkeziClient
{
    private $httpClient;
    private $apiKey;
    private $apiHash;
    private $defaultSender;

    public function __construct(string $apiKey, string $apiHash, ?string $defaultSender = null, ?string $httpClientType = null)
    {
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
        $this->defaultSender = $defaultSender;
        $this->httpClient = $this->resolveHttpClient($httpClientType);
    }

    private function resolveHttpClient(?string $preferredClient = null): HttpClientInterface
    {
        // Tercih edilen client belirtilmişse onu deneyelim
        if ($preferredClient === 'curl' && $this->isCurlAvailable()) {
            return new CurlHttpClient();
        }
        
        if ($preferredClient === 'guzzle' && $this->isGuzzleAvailable()) {
            return new GuzzleHttpClient();
        }

        // Tercih belirtilmemişse veya tercih edilen kullanılamıyorsa otomatik seçim yapalım
        if ($this->isCurlAvailable()) {
            return new CurlHttpClient();
        }

        if ($this->isGuzzleAvailable()) {
            return new GuzzleHttpClient();
        }

        throw new RuntimeException('No HTTP client available. Please install either PHP curl extension or Guzzle package.');
    }

    private function isCurlAvailable(): bool
    {
        return extension_loaded('curl') && function_exists('curl_init');
    }

    private function isGuzzleAvailable(): bool
    {
        return class_exists('\GuzzleHttp\Client');
    }

    public function sms(): SmsService
    {
        return new SmsService($this->httpClient, $this->apiKey, $this->apiHash, $this->defaultSender);
    }

    public function reports(): ReportService
    {
        return new ReportService($this->httpClient, $this->apiKey, $this->apiHash);
    }
    
    public function summary(): SummaryService
    {
        return new SummaryService($this->httpClient, $this->apiKey, $this->apiHash);
    }

    public function senders(): SenderService
    {
        return new SenderService($this->httpClient, $this->apiKey, $this->apiHash);
    }

    public function blacklist(): BlacklistService
    {
        return new BlacklistService($this->httpClient, $this->apiKey, $this->apiHash);
    }

    public function account(): AccountService
    {
        return new AccountService($this->httpClient, $this->apiKey, $this->apiHash);
    }

    public function webhook(): WebhookService
    {
        return new WebhookService();
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }   
    
    public function debug(): string
    {
        return json_encode([
            'payload' => json_decode($this->httpClient->getPayload()),
            'response' => $this->httpClient->getBody(),
            'status' => $this->httpClient->getStatusCode()
        ], JSON_PRETTY_PRINT);
    }
}
