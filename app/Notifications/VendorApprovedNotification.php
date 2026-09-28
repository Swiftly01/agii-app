<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your vendor account has been approved')
            ->greeting("Congratulations, {$notifiable->first_name}!")
            ->line('Your vendor account has been reviewed and approved.')
            ->line('You can now list products for sale — each new listing is briefly reviewed by our team before it goes live.')
            ->action('Start selling', route('products.create', ['type' => 'product']))
            ->line('Thanks for selling with us!');
    }
}
