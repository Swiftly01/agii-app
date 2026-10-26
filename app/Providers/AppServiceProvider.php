<?php

namespace App\Providers;

use App\Contracts\SmsGateway;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Services\Analytics\AdminAnalyticsService;
use App\Services\Analytics\Metrics;
use App\Services\Sms\SmsManager;
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
        // Controllers and channels depend on the contract, the manager picks the configured provider
        $this->app->singleton(SmsGateway::class, fn ($app) => new SmsManager($app));

        $this->registerAnalytics();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Product::observe(ProductObserver::class);

        Event::subscribe(\App\Listeners\AnalyticsEventSubscriber::class);
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