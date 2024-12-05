<?php

namespace IletiMerkezi\Services;

use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\SmsResponse;

class SmsService
{
    private $httpClient;
    private $apiKey;
    private $apiHash;
    private $defaultSender;
    private $sendDateTime = '';
    private $iys = '1';
    private $iysList = 'BIREYSEL';

    public function __construct(HttpClientInterface $httpClient, string $apiKey, string $apiHash, ?string $defaultSender = null)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiHash = $apiHash;
        $this->defaultSender = $defaultSender;
    }
    /**
     * Set the sendDateTime for scheduling messages.
     * GG/AA/YYYY SS:DD
     */
    public function schedule(string $sendDateTime): self
    {
        $this->sendDateTime = $sendDateTime;
        return $this;
    }

    /**
     * Set the IYS consent flag.
     */
    public function enableIysConsent(): self
    {
        $this->iys = 1;
        return $this;
    }

    public function disableIysConsent(): self
    {
        $this->iys = 0;
        return $this;
    }

    /**
     * Set the IYS list type (e.g., BIREYSEL or TACIR).
     */
    public function iysList(string $iysList): self
    {
        $this->iysList = $iysList;
        return $this;
    }

    public function send($recipients, ?string $message = null, ?string $sender = null): SmsResponse
    {
        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
                'order' => [
                    'sender' => $sender ?? $this->defaultSender,
                    'sendDateTime' => $this->sendDateTime,
                    'iys' => $this->iys,
                    'iysList' => $this->iysList,
                    'message' => $this->buildMessages($recipients, $message),
                ],
            ],
        ];

        $httpResponse = $this->httpClient->post('send-sms/json', [
            'json' => $payload,
        ]);

        return new SmsResponse($httpResponse->getBody(), $httpResponse->getStatusCode());
    }

        /**
     * Cancel a scheduled SMS order
     * 
     * @param string $orderId The order ID to cancel
     * @return SmsResponse
     * @throws \InvalidArgumentException if order cannot be found or already in progress
     */
    public function cancel(string $orderId): SmsResponse
    {
        $payload = [
            'request' => [
                'authentication' => [
                    'key' => $this->apiKey,
                    'hash' => $this->apiHash,
                ],
                'order' => [
                    'id' => $orderId,
                ],
            ],
        ];

        $httpResponse = $this->httpClient->post('cancel-order/json', [
            'json' => $payload,
        ]);

        return new SmsResponse($httpResponse->getBody(), $httpResponse->getStatusCode());
    }

    private function buildMessages($recipients, ?string $message): array
    {
        if (is_string($recipients)) {
            return [
                'text' => $message,
                'receipents' => [
                    'number' => [$recipients],
                ],
            ];
        }

        if (is_array($recipients) && $message !== null) {
            return [
                'text' => $message,
                'receipents' => [
                    'number' => $recipients,
                ],
            ];
        }

        if (is_array($recipients) && $message === null) {
            $messages = [];
            foreach ($recipients as $number => $text) {
                $messages[] = [
                    'text' => $text,
                    'receipents' => [
                        'number' => [$number],
                    ],
                ];
            }
            return $messages;
        }

        throw new \InvalidArgumentException('Invalid recipients or message format.');
    }
}