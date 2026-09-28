<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Product $product) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("\"{$this->product->title}\" is now live")
            ->greeting("Hi {$notifiable->first_name},")
            ->line("Your listing \"{$this->product->title}\" has been approved and is now visible to buyers.")
            ->action('View listing', route('product.show', $this->product->slug));
    }
}
