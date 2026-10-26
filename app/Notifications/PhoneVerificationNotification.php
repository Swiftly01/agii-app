<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

final class PhoneVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 60];


    public function __construct(
        private readonly string $code,
        private readonly int $ttlMinutes,
    ) {
        $this->onQueue(config('sms.queue'));
        // Dispatch only after the registration transaction has committed
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return [SmsChannel::class];
    }

    public function toSms(object $notifiable): string
    {
        return sprintf('Your %s verification code is %s. It expires in %d minutes. Never share this code.', config('app.name'), $this->code, $this->ttlMinutes);
    }
}
