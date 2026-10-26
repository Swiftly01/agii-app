<?php

namespace App\Services\Sms\Gateways;

use App\Contracts\SmsGateway;
use App\Exceptions\SmsDeliveryException;
use Illuminate\Http\Client\Factory as HttpFactory;

final class TwilioGateway implements SmsGateway
{
    private const ENDPOINT = 'https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json';

    public function __construct(
        private readonly HttpFactory $http,
        private readonly array $config,
    ) {}

    public function send(string $to, string $body): void
    {
        $response = $this->http
            ->timeout($this->config['timeout'])
            ->withBasicAuth($this->config['sid'], $this->config['token'])
            ->acceptJson()
            ->asForm()
            ->post(sprintf(self::ENDPOINT, $this->config['sid']), ['To' => $to, 'Body' => $body] + $this->sender());

        if ($response->failed()) {
            throw SmsDeliveryException::failed('twilio', $response->status(), $response->body());
        }
    }

    private function sender(): array
    {
        return filled($this->config['messaging_service_sid'])
            ? ['MessagingServiceSid' => $this->config['messaging_service_sid']]
            : ['From' => $this->config['from']];
    }
}
