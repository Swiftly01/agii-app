<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarketerApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your marketer account has been approved')
            ->greeting("Congratulations, {$notifiable->first_name}!")
            ->line('Your marketer application has been reviewed and approved.')
            ->line('You can now onboard vendors and earn commission on every subscription they activate.')
            ->action('Open your dashboard', route('marketer.dashboard'))
            ->line('Welcome to the team!');
    }
}
