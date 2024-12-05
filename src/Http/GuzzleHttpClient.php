<?php

namespace IletiMerkezi\Http;

use GuzzleHttp\Client;
use IletiMerkezi\VersionInfo;

class GuzzleHttpClient implements HttpClientInterface
{
    private $baseUrl = 'https://api.iletimerkezi.com/v1/';
    private $client;
    private $lastResponseBody;
    private $lastResponseStatusCode;
    private $lastPayload;
    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => 'IletiMerkezi-PHP/' . VersionInfo::string()
            ]
        ]);
    }

    public function post(string $url, array $options): HttpClientInterface
    {
        $response = $this->client->post("{$this->baseUrl}{$url}", $options);
        
        $this->lastPayload = json_encode($options['json']);
        $this->lastResponseBody = json_decode($response->getBody(), true);
        $this->lastResponseStatusCode = $response->getStatusCode();

        return $this;
    }

    public function getBody(): array
    {
        return $this->lastResponseBody;
    }

    public function getStatusCode(): int
    {
        return $this->lastResponseStatusCode;
    }

    public function getPayload(): string
    {
        return $this->lastPayload;
    }
}