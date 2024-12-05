<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Models\WebhookReport;

class WebhookService
{
    /**
     * Webhook'tan gelen veriyi işler
     */
    public function handle(): WebhookReport
    {
        $rawBody = file_get_contents('php://input');
        
        if (empty($rawBody)) {
            throw new \InvalidArgumentException('No POST data received');
        }

        $data = json_decode($rawBody, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON payload');
        }

        return new WebhookReport($data);
    }
} 