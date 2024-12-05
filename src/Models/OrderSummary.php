<?php

namespace IletiMerkezi\Models;

class OrderSummary
{
    private array $data;

    private const ORDER_STATUS_MESSAGES = [
        '113' => 'SENDING',
        '114' => 'COMPLETED',
        '115' => 'CANCELED'
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function id(): string
    {
        return $this->data['id'] ?? '';
    }

    public function statusCode(): string
    {
        return $this->data['status'] ?? '';
    }
    
    public function status(): string
    {
        return self::ORDER_STATUS_MESSAGES[$this->data['status']] ?? $this->data['status'];
    }

    public function total(): int
    {
        return (int) ($this->data['total'] ?? 0);
    }

    public function delivered(): int
    {
        return (int) ($this->data['delivered'] ?? 0);
    }

    public function undelivered(): int
    {
        return (int) ($this->data['undelivered'] ?? 0);
    }
    
    public function waiting(): int
    {
        return (int) ($this->data['waiting'] ?? 0);
    }

    public function submitAt(): string
    {
        return $this->data['submitAt'] ?? '';
    }
    
    public function sendAt(): string
    {
        return $this->data['sendAt'] ?? '';
    }
    
    public function sender(): string
    {
        return $this->data['sender'] ?? '';
    }
} 