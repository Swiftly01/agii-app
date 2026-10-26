<?php

namespace App\Services\Analytics\Metrics;

use App\Enums\AnalyticsEventType;
use App\Enums\AnalyticsPeriod;
use App\Models\AnalyticsEvent;
use App\Services\Analytics\Contracts\MetricGroup;
use Carbon\CarbonPeriod;

class TrendMetrics implements MetricGroup
{
    public function key(): string
    {
        return 'trend';
    }

    public function compute(AnalyticsPeriod $period): array
    {
        $daily = AnalyticsEvent::since($period->start())
            ->selectRaw('DATE(created_at) as day, type, COUNT(*) as total')
            ->groupBy('day', 'type')
            ->toBase()
            ->get()
            ->groupBy('type');

        $days = collect(CarbonPeriod::create($period->start(), now()->startOfDay()))
            ->map(fn ($date) => $date->toDateString());

        return [
            'labels' => $days->map(fn ($day) => date('M j', strtotime($day)))->all(),
            'logins' => $this->series($days, $daily->get(AnalyticsEventType::Login->value)),
            'clicks' => $this->series($days, $daily->get(AnalyticsEventType::ProductClick->value)),
        ];
    }

    private function series($days, $rows): array
    {
        $totals = collect($rows)->pluck('total', 'day');

        return $days->map(fn ($day) => (int) ($totals[$day] ?? 0))->all();
    }
}
