<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'tax_type',
        'tax_brackets',
        'personal_relief',
        'pension_rate',
        'nhf_rate',
        'is_active',
    ];

    protected $casts = [
        'tax_brackets' => 'array',
        'is_active'    => 'boolean',
    ];
}
