<?php

namespace App\Notifications;

use App\Notifications\Support\NotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Base class for every user-facing notification in the app.
 *
 * To add a new notification (new message, order shipped, price drop, ...):
 *   1. extend this class,
 *   2. implement payload() — title, body, url, ...,
 *   3. Notification::send($users, new YourNotification(...)) or $user->notify(...).
 * Channel selection, queueing, retries and push/inbox rendering are handled here.
 *
 * Always queued: one job per (user, channel), so a failure for one device
 * never re-sends to anyone else.
 */
abstract class AppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** Drop the job silently if a model it references was deleted meanwhile. */
    public $deleteWhenMissingModels = true;

    abstract public function payload(object $notifiable): NotificationPayload;

    /**
     * Inbox record always; push only for users with at least one subscribed browser.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->pushSubscriptions->isNotEmpty()) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    /** @return array<string, string> */
    public function viaQueues(): array
    {
        $queue = config('notifications.queue');

        return ['database' => $queue, WebPushChannel::class => $queue];
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return $this->payload($notifiable)->toArray();
    }

    public function toWebPush(object $notifiable, Notification $notification): WebPushMessage
    {
        $payload = $this->payload($notifiable);
        $config = config('notifications.push');

        $message = (new WebPushMessage)
            ->title($payload->title)
            ->body($payload->body)
            ->icon(asset($config['icon']))
            ->badge(asset($config['badge']))
            ->data(['url' => $payload->url] + $payload->data)
            ->options(['TTL' => $config['ttl'], 'urgency' => $config['urgency']]);

        if ($payload->tag) {
            $message->tag($payload->tag);
        }

        if ($payload->image) {
            $message->image($payload->image);
        }

        return $message;
    }
}
