<?php

namespace IletiMerkezi\Responses;

use IletiMerkezi\BaseResponse;

class SenderResponse extends BaseResponse
{
    private array $senders = [];

    protected function customizeData(): void
    {
        $this->senders = $this->data['senders']['sender'] ?? [];
    }

    public function senders(): array
    {
        return $this->senders;
    }
} 