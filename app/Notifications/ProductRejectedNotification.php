<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Product $product,
        public readonly string $reason,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("\"{$this->product->title}\" was not approved")
            ->greeting("Hi {$notifiable->first_name},")
            ->line("Your listing \"{$this->product->title}\" was not approved.")
            ->line("Reason: {$this->reason}")
            ->line('You can edit the listing and resubmit it for review.')
            ->action('Edit listing', route('products.edit', $this->product->slug));
    }
}
