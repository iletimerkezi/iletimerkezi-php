<?php

namespace IletiMerkezi\Responses;

use IletiMerkezi\BaseResponse;

class ReportResponse extends BaseResponse
{
    private array $messages = [];
    private int $currentPage;

    private const ORDER_STATUS_MESSAGES = [
        '113' => 'SENDING',
        '114' => 'COMPLETED',
        '115' => 'CANCELED'
    ];

    private const MESSAGE_STATUS_MESSAGES = [
        '110' => 'WAITING',
        '111' => 'DELIVERED',
        '112' => 'UNDELIVERED'
    ];
    public function __construct(array $responseBody, int $httpStatusCode, int $currentPage)
    {
        parent::__construct($responseBody, $httpStatusCode);
        $this->currentPage = $currentPage;
    }

    protected function customizeData(): void
    {
        $this->messages = $this->data['order']['message'] ?? [];
    }

    // Order getters
    public function orderId(): string
    {
        return $this->data['order']['id'] ?? '';
    }

    public function orderStatus(): string
    {
        $status = $this->data['order']['status'] ?? '';
        return self::ORDER_STATUS_MESSAGES[$status] ?? $status;
    }

    public function orderStatusCode(): int
    {
        return (int) ($this->data['order']['status'] ?? 0);
    }

    public function total()
    {
        return $this->data['order']['total'] ?? 0;
    }

    public function delivered(): int
    {
        return $this->data['order']['delivered'] ?? 0;
    }

    public function undelivered(): int
    {
        return $this->data['order']['undelivered'] ?? 0;
    }

    public function waiting(): int 
    {
        return $this->data['order']['waiting'] ?? 0;
    }

    public function submitAt(): string
    {
        return $this->data['order']['submitAt'] ?? '';
    }

    public function sendAt(): string
    {
        return $this->data['order']['sendAt'] ?? '';
    }

    public function sender(): string
    {
        return $this->data['order']['sender'] ?? '';
    }

    // Message getters
    public function messages(): array
    {
        $messages = [];
        foreach($this->data['order']['message'] as $message) {
            $messages[] = [
                'number' => $message['number'],
                'status' => self::MESSAGE_STATUS_MESSAGES[$message['status']] ?? $message['status'],
                'status_code' => $message['status']
            ];
        }

        return $messages;
    }

    public function totalPages(): int
    {
        return ceil($this->total() / 1000);
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage() < $this->totalPages();
    }
}