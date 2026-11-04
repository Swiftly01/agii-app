<?php

namespace App\Services\Staff;

use App\Models\StaffProfile;
use Illuminate\Support\Str;

class StaffDepartments
{
    // Form value that reveals the free text field for a new category
    public const OTHER = 'other';

    // Configured departments plus every custom one admins added before, so a category is typed only once
    public function options(): array
    {
        $existing = StaffProfile::query()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        return collect(config('staff.departments'))
            ->merge($existing)
            ->unique(fn (string $department) => Str::lower($department))
            ->values()
            ->all();
    }

    // Turns the select and the free text field into the single value that is stored
    public function resolve(string $selected, ?string $other): string
    {
        if ($selected !== self::OTHER) {
            return $selected;
        }

        return $this->matchExisting(trim((string) $other));
    }

    // Reuses the stored spelling when the typed category already exists, ignoring case
    private function matchExisting(string $typed): string
    {
        $match = collect($this->options())
            ->first(fn (string $department) => Str::lower($department) === Str::lower($typed));

        return $match ?? $typed;
    }
}
