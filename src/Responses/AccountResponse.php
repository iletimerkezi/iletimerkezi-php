<?php

namespace IletiMerkezi\Responses;

use IletiMerkezi\BaseResponse;

class AccountResponse extends BaseResponse
{
    private $amount;
    private $sms;

    protected function customizeData(): void
    {
        $balance = $this->data['balance'] ?? [];
        $this->amount = $balance['amount'] ?? '0';
        $this->sms = $balance['sms'] ?? '0';
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function credits(): string
    {
        return $this->sms;
    }
} 