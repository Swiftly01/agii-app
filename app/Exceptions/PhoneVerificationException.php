<?php

namespace App\Exceptions;

use RuntimeException;

final class PhoneVerificationException extends RuntimeException
{
    public static function resendThrottled(int $seconds): self
    {
        return new self("Please wait {$seconds} seconds before requesting another code.");
    }

    public static function hourlyLimitReached(int $seconds): self
    {
        return new self('Too many codes requested. Please try again in '.max(1, (int) ceil($seconds / 60)).' minute(s).');
    }

    public static function expired(): self
    {
        return new self('This code has expired. Please request a new one.');
    }

    public static function invalid(int $attemptsLeft): self
    {
        return new self("That code is incorrect. {$attemptsLeft} attempt(s) left.");
    }

    public static function lockedOut(): self
    {
        return new self('Too many incorrect attempts. Please request a new code.');
    }
}
