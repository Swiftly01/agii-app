<?php

namespace App\Services\Analytics\Contracts;

use App\Enums\AnalyticsPeriod;

interface MetricGroup
{
    // Key the group's results are stored under in the report
    public function key(): string;

    public function compute(AnalyticsPeriod $period): array;
}
