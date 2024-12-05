<?php

namespace IletiMerkezi\Responses;

use IletiMerkezi\BaseResponse;

class BlacklistResponse extends BaseResponse
{
    private array $numbers = [];
    private int $currentPage;

    public function __construct(array $responseBody, int $httpStatusCode, int $currentPage)
    {
        parent::__construct($responseBody, $httpStatusCode);
        $this->currentPage = $currentPage;
    }

    protected function customizeData(): void
    {
        $this->numbers = $this->data['blacklist']['number'] ?? [];
    }

    public function count(): int
    {
        return (int) ($this->data['blacklist']['count'] ?? 0);
    }

    public function numbers(): array
    {
        return $this->numbers;
    }

    public function totalPages(): int
    {
        return ceil($this->count() / 1000);
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function hasMorePage(): bool 
    {
        return $this->currentPage() < $this->totalPages();
    }
} 