<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'calculation_type',
        'amount',
        'percentage',
        'formula',
        'is_taxable',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'is_active'  => 'boolean',
    ];
}
