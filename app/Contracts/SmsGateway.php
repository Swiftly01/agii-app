<?php

namespace App\Contracts;

interface SmsGateway
{
    // Recipient is an E.164 number such as +2348012345678
    public function send(string $to, string $body): void;
}
