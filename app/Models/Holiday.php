<?php

// app/Models/Holiday.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'date', 'type', 'description', 'repeats_annually'
    ];

    protected $casts = [
        'date' => 'date',
        'repeats_annually' => 'boolean'
    ];

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('date', '>=', today())
                     ->where('date', '<=', today()->addDays($days))
                     ->orderBy('date');
    }
}