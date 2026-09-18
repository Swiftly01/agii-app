<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_profile_id',
        'payroll_id',
        'payslip_number',
        'payment_date',
        'basic_salary',
        'gross_earning',
        'total_deductions',
        'net_salary',
        'earnings',
        'deductions',
        'working_days',
        'present_days',
        'overtime_hours',
        'overtime_amount',
        'leave_deductions',
        'advance_deductions',
        'status',
        'notes',
        'payment_method',
        'transaction_reference',
        'is_viewed',
        'viewed_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'earnings'     => 'array',
        'deductions'   => 'array',
        'is_viewed'    => 'boolean',
        'viewed_at'    => 'datetime',
    ];

    /** Relationships */

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }
}
