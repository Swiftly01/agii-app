<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendProductNotificationChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public $deleteWhenMissingModels = true;

    /** @param  array<int, int>  $userIds */
    public function __construct(
        public readonly Product $product,
        public readonly array $userIds,
    ) {
        $this->onQueue(config('notifications.queue'));
    }

    public function handle(): void
    {
        // Eager-load subscriptions: AppNotification::via() reads them per user.
        $buyers = User::query()
            ->whereKey($this->userIds)
            ->with('pushSubscriptions')
            ->get();

        Notification::send($buyers, new NewProductNotification($this->product));
    }
}
