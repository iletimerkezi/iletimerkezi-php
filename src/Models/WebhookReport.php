<?php

namespace IletiMerkezi\Models;

class WebhookReport
{
    private $id;
    private $packetId;
    private $status;
    private $to;
    private $body;

    public function __construct(array $data)
    {
        $report = $data['report'] ?? [];
        $this->id = $report['id'] ?? '';
        $this->packetId = $report['packet_id'] ?? '';
        $this->status = $report['status'] ?? '';
        $this->to = $report['to'] ?? '';
        $this->body = $report['body'] ?? '';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPacketId(): string
    {
        return $this->packetId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isUndelivered(): bool
    {
        return $this->status === 'undelivered';
    }
} 