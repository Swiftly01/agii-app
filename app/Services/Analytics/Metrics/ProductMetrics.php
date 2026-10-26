<?php

namespace App\Services\Analytics\Metrics;

use App\Enums\AnalyticsEventType;
use App\Enums\AnalyticsPeriod;
use App\Models\AnalyticsEvent;
use App\Models\Product;
use App\Services\Analytics\Contracts\MetricGroup;

class ProductMetrics implements MetricGroup
{
    private const TOP_LIMIT = 5;

    public function key(): string
    {
        return 'products';
    }

    public function compute(AnalyticsPeriod $period): array
    {
        $clicks = AnalyticsEvent::ofType(AnalyticsEventType::ProductClick)->since($period->start());

        return [
            'total' => Product::count(),
            'new' => Product::where('created_at', '>=', $period->start())->count(),
            'byStatus' => Product::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status')->all(),
            'clicks' => (clone $clicks)->count(),
            'uniqueVisitors' => (clone $clicks)->distinct('session_id')->count('session_id'),
            'loggedInClicks' => (clone $clicks)->whereNotNull('user_id')->count(),
            'guestClicks' => (clone $clicks)->whereNull('user_id')->count(),
            'topClicked' => $this->topClicked(clone $clicks),
        ];
    }

    private function topClicked($clicks): array
    {
        $top = $clicks
            ->selectRaw('subject_id, COUNT(*) as clicks')
            ->where('subject_type', (new Product)->getMorphClass())
            ->groupBy('subject_id')
            ->orderByDesc('clicks')
            ->limit(self::TOP_LIMIT)
            ->get();

        $titles = Product::whereIn('id', $top->pluck('subject_id'))->pluck('title', 'id');

        return $top->map(fn ($row) => [
            'title' => $titles[$row->subject_id] ?? 'Deleted product',
            'clicks' => (int) $row->clicks,
        ])->all();
    }
}
