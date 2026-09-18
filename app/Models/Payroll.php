<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period',
        'start_date',
        'end_date',
        'status',
        'total_gross',
        'total_deductions',
        'total_net',
        'total_staff',
        'prepared_by',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'approved_at' => 'datetime',
    ];

    /** Relationships */

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
