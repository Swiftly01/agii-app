<?php

namespace App\Providers;

use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Product::observe(ProductObserver::class);

        $this->registerApprovalListeners();
        $this->registerNotificationListeners();
    }

    /**
     * Vendor/product approval side-effects (notifications, etc.) are wired
     * as events rather than inline in the services/controllers, so new
     * side-effects (e.g. Slack alerts, analytics) can be added later by
     * registering another listener here — nothing else has to change.
     */
    protected function registerApprovalListeners(): void
    {
        Event::listen(
            \App\Events\VendorApproved::class,
            \App\Listeners\SendVendorApprovalNotification::class,
        );

        Event::listen(
            \App\Events\VendorRejected::class,
            \App\Listeners\SendVendorRejectionNotification::class,
        );

        Event::listen(
            \App\Events\ProductApproved::class,
            \App\Listeners\SendProductApprovalNotification::class,
        );

        Event::listen(
            \App\Events\ProductRejected::class,
            \App\Listeners\SendProductRejectionNotification::class,
        );
    }

    /**
     * Buyer-facing notifications. New notification triggers go here: map a
     * domain event to a listener that sends an AppNotification subclass.
     */
    protected function registerNotificationListeners(): void
    {
        Event::listen(
            \App\Events\ProductPublished::class,
            \App\Listeners\NotifyBuyersOfNewProduct::class,
        );
    }
}
