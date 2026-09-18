<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'company_contribution',
        'employee_contribution',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
