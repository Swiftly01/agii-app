<?php

namespace App\Services\Analytics\Metrics;

use App\Enums\AnalyticsPeriod;
use App\Models\User;
use App\Services\Analytics\Contracts\MetricGroup;

class UserMetrics implements MetricGroup
{
    public function key(): string
    {
        return 'users';
    }

    public function compute(AnalyticsPeriod $period): array
    {
        $byRole = User::query()->selectRaw('user_type, COUNT(*) as total')->groupBy('user_type')->pluck('total', 'user_type');

        return [
            'total' => $byRole->sum(),
            'new' => User::where('created_at', '>=', $period->start())->count(),
            'sellers' => (int) $byRole->get('vendor', 0),
            'buyers' => (int) $byRole->get('customer', 0),
            'marketers' => (int) $byRole->get('marketer', 0),
            'admins' => (int) $byRole->get('admin', 0),
            'verified' => User::whereNotNull('email_verified_at')->orWhereNotNull('phone_verified_at')->count(),
            'sellerApproval' => User::where('user_type', 'vendor')
                ->selectRaw('vendor_status, COUNT(*) as total')
                ->groupBy('vendor_status')
                ->pluck('total', 'vendor_status')
                ->all(),
        ];
    }
}
