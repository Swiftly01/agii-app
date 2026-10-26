<?php

namespace App\Http\Controllers;

use App\Enums\AnalyticsPeriod;
use App\Services\Analytics\AdminAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAnalyticsController extends Controller
{
    public function __construct(private readonly AdminAnalyticsService $analytics) {}

    public function __invoke(Request $request): View
    {
        $period = AnalyticsPeriod::fromInput($request->query('period'));

        return view('admin.analytics.index', [
            'period' => $period,
            'periods' => AnalyticsPeriod::cases(),
            'report' => $this->analytics->report($period),
        ]);
    }
}
