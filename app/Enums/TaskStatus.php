<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In progress',
            self::Completed => 'Completed',
        };
    }

    // Statuses a task is allowed to move to from this one
    public function canTransitionTo(self $next): bool
    {
        return match ($this) {
            self::Pending => in_array($next, [self::InProgress, self::Completed], true),
            self::InProgress => $next === self::Completed,
            self::Completed => false,
        };
    }

    // Plain values, handy for validation and filters
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
