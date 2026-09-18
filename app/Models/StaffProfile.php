<?php

// app/Models/StaffProfile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employment_application_id',
        'staff_id',
        'department',
        'designation',
        'date_of_employment',
        'employment_type',
        'salary',
        'bank_name',
        'account_number',
        'account_name',
        'pension_number',
        'tax_id',
        'next_of_review',
        'emergency_contact',
        'status',
        'notes'
    ];

    protected $casts = [
        'emergency_contact' => 'array',
        'date_of_employment' => 'date',
        'next_of_review' => 'date',
        'salary' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employmentApplication()
    {
        return $this->belongsTo(EmploymentApplication::class);
    }
    
    // app/Models/StaffProfile.php
    public function documents()
    {
        return $this->hasMany(StaffDocument::class);
    }

    public function getActiveCertificates()
    {
        return $this->documents()
            ->where('document_type', 'certificate')
            ->where('status', 'approved')
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>', now());
            })
            ->get();
    }
    
    public function getPendingDocuments()
    {
        return $this->documents()->where('status', 'pending')->get();
    }
    
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Payslips for this staff
     */
    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    /**
     * Leave applications for this staff
     */
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}