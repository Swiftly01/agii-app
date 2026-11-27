<?php

namespace App\Enums;

// Mirrors the `marketers.education_level` column so qualification checks never rely on magic strings
enum EducationLevel: string
{
    case Ssce = 'ssce';
    case Ond = 'ond';
    case Nd = 'nd';
    case Hnd = 'hnd';
    case Bsc = 'bsc';
    case Msc = 'msc';
    case Phd = 'phd';

    public function label(): string
    {
        return match ($this) {
            self::Ssce => 'SSCE',
            self::Ond => 'OND',
            self::Nd => 'ND',
            self::Hnd => 'HND',
            self::Bsc => 'BSc',
            self::Msc => 'MSc',
            self::Phd => 'PhD',
        };
    }

    // Value => label pairs for building the dropdown in one line
    public static function options(): array
    {
        return array_column(
            array_map(fn (self $level) => ['value' => $level->value, 'label' => $level->label()], self::cases()),
            'label',
            'value',
        );
    }
}
