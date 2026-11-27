<?php

namespace App\Providers;

use App\Contracts\SmsGateway;
use App\Models\Product;
use App\Models\VendorSubscription;
use App\Observers\ProductObserver;
use App\Observers\VendorSubscriptionObserver;
use App\Services\Analytics\AdminAnalyticsService;
use App\Services\Analytics\Metrics;
use App\Services\BoostPaymentService;
use App\Services\Payments\PaystackWebhookDispatcher;
use App\Services\Sms\SmsManager;
use App\Services\Spotlight\SpotlightPaymentService;
use App\View\Composers\HeroSpotlightComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Controllers and channels depend on the contract, the manager picks the configured provider
        $this->app->singleton(SmsGateway::class, fn ($app) => new SmsManager($app));

        $this->registerAnalytics();

        $this->registerPaystackHandlers();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Product::observe(ProductObserver::class);

        VendorSubscription::observe(VendorSubscriptionObserver::class);

        Event::subscribe(\App\Listeners\AnalyticsEventSubscriber::class);

        // The hero partial always receives the paid spotlight products
        View::composer('home.hero-spotlight', HeroSpotlightComposer::class);
    }

    // Add a class implementing PaystackChargeHandler here and the central webhook settles its payments too
    protected function registerPaystackHandlers(): void
    {
        $this->app->tag([
            BoostPaymentService::class,
            SpotlightPaymentService::class,
        ], 'paystack.charge_handlers');

        $this->app->when(PaystackWebhookDispatcher::class)->needs('$handlers')->giveTagged('paystack.charge_handlers');
    }

    // Listeners in app/Listeners are auto-discovered from their typed handle() method, so they must not be registered again here
    // Register a new MetricGroup class here and it appears in the admin report
    protected function registerAnalytics(): void
    {
        $this->app->tag([
            Metrics\UserMetrics::class,
            Metrics\LoginMetrics::class,
            Metrics\ProductMetrics::class,
            Metrics\EngagementMetrics::class,
            Metrics\TrendMetrics::class,
        ], 'analytics.metrics');

        $this->app->when(AdminAnalyticsService::class)->needs('$groups')->giveTagged('analytics.metrics');
    }
}