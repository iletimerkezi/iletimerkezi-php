<?php

namespace IletiMerkezi\Responses;

use IletiMerkezi\BaseResponse;

class SmsResponse extends BaseResponse
{
    private ?string $orderId;

    protected function customizeData(): void
    {
        $this->orderId = $this->data['order']['id'] ?? null;
    }

    public function orderId(): ?string
    {
        return $this->orderId;
    }
}