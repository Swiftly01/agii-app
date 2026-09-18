<?php

// app/Models/LeaveType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'annual_entitlement', 'carry_forward',
        'max_carry_forward', 'description', 'requires_approval',
        'allowed_days', 'is_active'
    ];

    protected $casts = [
        'allowed_days' => 'array',
        'is_active' => 'boolean',
        'carry_forward' => 'boolean',
        'requires_approval' => 'boolean'
    ];

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}