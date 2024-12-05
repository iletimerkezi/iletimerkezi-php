<?php

namespace IletiMerkezi\Http;

use IletiMerkezi\VersionInfo;

class CurlHttpClient implements HttpClientInterface
{
    private $baseUrl = 'https://api.iletimerkezi.com/v1/';
    private $lastPayload;
    private $lastResponseBody;
    private $lastResponseStatusCode;

    public function post(string $url, array $options): HttpClientInterface
    {
        $ch = curl_init("{$this->baseUrl}{$url}");

        $payload = json_encode($options['json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'User-Agent:' . 'IletiMerkezi-PHP/' . VersionInfo::string()
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }

        $this->lastPayload = $payload;
        $this->lastResponseStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->lastResponseBody = json_decode($response, true);

        curl_close($ch);

        return $this;
    }

    public function getBody(): array
    {
        return $this->lastResponseBody;
    }

    public function getStatusCode(): int
    {
        return (int) $this->lastResponseStatusCode;
    }

    public function getPayload(): string
    {
        return $this->lastPayload;
    }
}
