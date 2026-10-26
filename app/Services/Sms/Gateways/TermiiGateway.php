<?php

namespace App\Services\Sms\Gateways;

use App\Contracts\SmsGateway;
use App\Exceptions\SmsDeliveryException;
use Illuminate\Http\Client\Factory as HttpFactory;

final class TermiiGateway implements SmsGateway
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly array $config,
    ) {}

    public function send(string $to, string $body): void
    {
        $response = $this->http
            ->baseUrl($this->config['base_url'])
            ->timeout($this->config['timeout'])
            ->acceptJson()
            ->asJson()
            ->post('/api/sms/send', [
                'api_key' => $this->config['api_key'],
                // Termii expects international format without the plus sign
                'to' => ltrim($to, '+'),
                'from' => $this->config['sender_id'],
                'sms' => $body,
                'type' => 'plain',
                'channel' => $this->config['channel'],
            ]);

        if ($response->failed()) {
            throw SmsDeliveryException::failed('termii', $response->status(), $response->body());
        }
    }
}
