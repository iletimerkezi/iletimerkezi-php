<?php

namespace IletiMerkezi;

abstract class BaseResponse
{
    protected $statusCode;
    protected $message;
    protected $data;
    protected $isSuccessful;

    public function __construct(array $responseBody, int $httpStatusCode)
    {
        $this->statusCode = $httpStatusCode;
        $this->message = $responseBody['response']['status']['message'] ?? 'Unknown error';
        $this->data = $responseBody['response'] ?? [];
        $this->isSuccessful = $httpStatusCode === 200;

        $this->customizeData();
    }

    abstract protected function customizeData(): void;

    public function ok(): bool
    {
        return $this->isSuccessful;
    }

    public function code(): int
    {
        return $this->statusCode;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}