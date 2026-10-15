<?php

namespace App\Listeners;

use App\Events\ProductPublished;
use App\Services\ProductAnnouncementService;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBuyersOfNewProduct implements ShouldQueue
{
    public function __construct(
        private readonly ProductAnnouncementService $announcements,
    ) {}

    public function viaQueue(): string
    {
        return config('notifications.queue');
    }

    public function handle(ProductPublished $event): void
    {
        $this->announcements->announce($event->product);
    }
}