<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly string $reason) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update on your vendor application')
            ->greeting("Hi {$notifiable->first_name},")
            ->line('We reviewed your application to become a vendor and are unable to approve it at this time.')
            ->line("Reason: {$this->reason}")
            ->line('You can update your details and reply to this email if you believe this was a mistake.');
    }
}
