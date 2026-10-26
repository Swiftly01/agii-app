<?php

namespace App\Enums;

use Carbon\CarbonImmutable;

enum AnalyticsPeriod: string
{
    case Today = 'today';
    case Week = '7d';
    case Month = '30d';
    case Quarter = '90d';

    public static function fromInput(?string $value): self
    {
        return self::tryFrom((string) $value) ?? self::Week;
    }

    public function label(): string
    {
        return match ($this) {
            self::Today => 'Today',
            self::Week => 'Last 7 days',
            self::Month => 'Last 30 days',
            self::Quarter => 'Last 90 days',
        };
    }

    public function start(): CarbonImmutable
    {
        $today = CarbonImmutable::now()->startOfDay();

        return match ($this) {
            self::Today => $today,
            self::Week => $today->subDays(6),
            self::Month => $today->subDays(29),
            self::Quarter => $today->subDays(89),
        };
    }
}
