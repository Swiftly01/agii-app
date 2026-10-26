<?php

namespace App\Services\PhoneVerification;

use App\Exceptions\PhoneVerificationException;
use App\Models\User;
use App\Notifications\PhoneVerificationNotification;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;

final class PhoneVerificationService
{
    public function __construct(
        private readonly Cache $cache,
        private readonly RateLimiter $limiter,
        private readonly Config $config,
    ) {}

    public function sendCode(User $user): void
    {
        $phone = (string) $user->phone;

        $this->guardSendLimits($phone);

        $code = $this->generateCode();

        $this->cache->put($this->codeKey($phone), $this->digest($phone, $code), $this->ttlSeconds());
        $this->limiter->clear($this->attemptsKey($phone));

        $user->notify(new PhoneVerificationNotification($code, $this->ttlMinutes()));
    }

    public function verifyCode(User $user, string $code): void
    {
        $phone = (string) $user->phone;
        $maxAttempts = $this->config->get('phone_verification.max_attempts');

        if ($this->limiter->tooManyAttempts($this->attemptsKey($phone), $maxAttempts)) {
            throw PhoneVerificationException::lockedOut();
        }

        $expected = $this->cache->get($this->codeKey($phone));

        if ($expected === null) {
            throw PhoneVerificationException::expired();
        }

        if (! hash_equals($expected, $this->digest($phone, $code))) {
            $this->limiter->hit($this->attemptsKey($phone), $this->ttlSeconds());

            throw PhoneVerificationException::invalid($this->limiter->remaining($this->attemptsKey($phone), $maxAttempts));
        }

        $this->cache->forget($this->codeKey($phone));
        $this->limiter->clear($this->attemptsKey($phone));

        $user->forceFill(['phone_verified_at' => now()])->save();
    }

    public function resendAvailableIn(User $user): int
    {
        return $this->limiter->availableIn($this->cooldownKey((string) $user->phone));
    }

    private function guardSendLimits(string $phone): void
    {
        if ($this->limiter->tooManyAttempts($this->cooldownKey($phone), 1)) {
            throw PhoneVerificationException::resendThrottled($this->limiter->availableIn($this->cooldownKey($phone)));
        }

        $hourlyCap = $this->config->get('phone_verification.max_sends_per_hour');

        if ($this->limiter->tooManyAttempts($this->hourlyKey($phone), $hourlyCap)) {
            throw PhoneVerificationException::hourlyLimitReached($this->limiter->availableIn($this->hourlyKey($phone)));
        }

        $this->limiter->hit($this->cooldownKey($phone), $this->config->get('phone_verification.resend_cooldown_seconds'));
        $this->limiter->hit($this->hourlyKey($phone), 3600);
    }

    private function generateCode(): string
    {
        $length = $this->config->get('phone_verification.code_length');

        return str_pad((string) random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    // Only a keyed hash is cached so a cache leak never exposes live codes
    private function digest(string $phone, string $code): string
    {
        return hash_hmac('sha256', $phone.'|'.$code, (string) $this->config->get('app.key'));
    }

    private function ttlMinutes(): int
    {
        return $this->config->get('phone_verification.ttl_minutes');
    }

    private function ttlSeconds(): int
    {
        return $this->ttlMinutes() * 60;
    }

    private function codeKey(string $phone): string
    {
        return "phone-otp:code:{$phone}";
    }

    private function attemptsKey(string $phone): string
    {
        return "phone-otp:attempts:{$phone}";
    }

    private function cooldownKey(string $phone): string
    {
        return "phone-otp:cooldown:{$phone}";
    }

    private function hourlyKey(string $phone): string
    {
        return "phone-otp:hourly:{$phone}";
    }
}
