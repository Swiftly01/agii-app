<?php

namespace App\Services\Sms\Gateways;

use App\Contracts\SmsGateway;
use Psr\Log\LoggerInterface;

final class LogGateway implements SmsGateway
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function send(string $to, string $body): void
    {
        $this->logger->info('SMS (log driver)', ['to' => $to, 'body' => $body]);
    }
}
