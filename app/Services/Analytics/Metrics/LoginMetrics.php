<?php

namespace App\Services\Analytics\Metrics;

use App\Enums\AnalyticsEventType;
use App\Enums\AnalyticsPeriod;
use App\Models\AnalyticsEvent;
use App\Services\Analytics\Contracts\MetricGroup;
use Illuminate\Support\Facades\DB;

class LoginMetrics implements MetricGroup
{
    private const ONLINE_WINDOW_MINUTES = 5;

    public function key(): string
    {
        return 'logins';
    }

    public function compute(AnalyticsPeriod $period): array
    {
        $logins = AnalyticsEvent::ofType(AnalyticsEventType::Login)->since($period->start());

        $byRole = (clone $logins)
            ->selectRaw('user_type, COUNT(*) as logins, COUNT(DISTINCT user_id) as users')
            ->groupBy('user_type')
            ->get()
            ->keyBy('user_type');

        return [
            'total' => (clone $logins)->count(),
            'uniqueUsers' => (clone $logins)->distinct('user_id')->count('user_id'),
            'sellerLogins' => (int) ($byRole['vendor']->logins ?? 0),
            'sellerUsers' => (int) ($byRole['vendor']->users ?? 0),
            'buyerLogins' => (int) ($byRole['customer']->logins ?? 0),
            'buyerUsers' => (int) ($byRole['customer']->users ?? 0),
            'onlineNow' => $this->onlineNow(),
        ];
    }

    // Only the database session driver can tell who is online right now
    private function onlineNow(): ?int
    {
        if (config('session.driver') !== 'database') {
            return null;
        }

        return DB::table(config('session.table', 'sessions'))
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(self::ONLINE_WINDOW_MINUTES)->timestamp)
            ->distinct('user_id')
            ->count('user_id');
    }
}
