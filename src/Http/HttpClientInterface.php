<?php

namespace IletiMerkezi\Http;

interface HttpClientInterface
{
    public function post(string $url, array $options): HttpClientInterface;
    public function getBody(): array;
    public function getStatusCode(): int;
    public function getPayload(): string;
}
