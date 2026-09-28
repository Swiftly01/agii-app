<?php

namespace App\Providers;

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

        $this->registerApprovalListeners();
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
}
