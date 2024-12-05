<?php

namespace IletiMerkezi\Responses;

use IletiMerkezi\BaseResponse;
use IletiMerkezi\Models\OrderSummary;

class SummaryResponse extends BaseResponse
{
    private array $orders = [];
    private int $currentPage;
    
    public function __construct(array $responseBody, int $httpStatusCode, int $currentPage)
    {
        parent::__construct($responseBody, $httpStatusCode);
        $this->currentPage = $currentPage;
    }

    protected function customizeData(): void
    {
    }

    public function count(): int
    {
        return (int) ($this->data['count'] ?? 0);
    }

    public function orders(): array
    {   
        $this->orders = [];
        foreach($this->data['orders'] ?? [] as $order) {
            $this->orders[] = new OrderSummary($order);
        }

        return $this->orders;
    }

    public function totalPages(): int
    {
        return ceil($this->count() / 30) ?? 1;
    }

    public function currentPage(): int
    {
        return (int) $this->currentPage;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage() < $this->totalPages();
    }
} 
