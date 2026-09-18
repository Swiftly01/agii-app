<?php
// app/Models/LeaveApplication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_profile_id', 'leave_type_id', 'start_date', 'end_date',
        'total_days', 'reason', 'status', 'approved_by', 'approved_at',
        'approval_notes', 'emergency_contact', 'handover_to', 'attachment_path'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'emergency_contact' => 'array'
    ];

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }
}
