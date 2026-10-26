<?php

namespace App\Services\Analytics;

use App\Enums\AnalyticsPeriod;
use App\Services\Analytics\Contracts\MetricGroup;
use Illuminate\Support\Facades\Cache;

class AdminAnalyticsService
{
    private const CACHE_TTL_SECONDS = 300;

    /** @param iterable<MetricGroup> $groups */
    public function __construct(private readonly iterable $groups) {}

    public function report(AnalyticsPeriod $period): array
    {
        return Cache::remember(
            "admin.analytics.{$period->value}",
            self::CACHE_TTL_SECONDS,
            fn () => $this->build($period),
        );
    }

    private function build(AnalyticsPeriod $period): array
    {
        $report = [];

        foreach ($this->groups as $group) {
            $report[$group->key()] = $group->compute($period);
        }

        return $report;
    }
}
