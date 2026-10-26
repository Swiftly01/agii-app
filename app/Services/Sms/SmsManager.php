<?php

namespace App\Services\Sms;

use App\Contracts\SmsGateway;
use App\Services\Sms\Gateways\LogGateway;
use App\Services\Sms\Gateways\TermiiGateway;
use App\Services\Sms\Gateways\TwilioGateway;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Manager;
use Psr\Log\LoggerInterface;

final class SmsManager extends Manager implements SmsGateway
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('sms.default');
    }

    public function send(string $to, string $body): void
    {
        $this->driver()->send($to, $body);
    }

    // Adding a provider means adding one createXDriver method, or calling SmsManager::extend()
    protected function createTermiiDriver(): SmsGateway
    {
        return new TermiiGateway($this->container->make(HttpFactory::class), $this->config->get('sms.drivers.termii'));
    }

    protected function createTwilioDriver(): SmsGateway
    {
        return new TwilioGateway($this->container->make(HttpFactory::class), $this->config->get('sms.drivers.twilio'));
    }

    protected function createLogDriver(): SmsGateway
    {
        return new LogGateway($this->container->make(LoggerInterface::class));
    }
}
