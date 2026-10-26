<?php

namespace App\Services\Analytics\Metrics;

use App\Enums\AnalyticsPeriod;
use App\Models\Review;
use App\Models\VendorContact;
use App\Services\Analytics\Contracts\MetricGroup;
use Illuminate\Support\Facades\DB;

class EngagementMetrics implements MetricGroup
{
    public function key(): string
    {
        return 'engagement';
    }

    public function compute(AnalyticsPeriod $period): array
    {
        return [
            'inquiries' => VendorContact::where('created_at', '>=', $period->start())->count(),
            'reviews' => Review::where('created_at', '>=', $period->start())->count(),
            'pushSubscribers' => DB::table('push_subscriptions')->distinct('subscribable_id')->count('subscribable_id'),
        ];
    }
}
