<?php

namespace App\Exceptions;

use Illuminate\Support\Str;
use RuntimeException;

final class SmsDeliveryException extends RuntimeException
{
    public static function failed(string $driver, int $status, string $reason): self
    {
        return new self(sprintf('SMS delivery via [%s] failed with status %d: %s', $driver, $status, Str::limit($reason, 200)));
    }
}
