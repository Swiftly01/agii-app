<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrQuery extends Model
{
    protected $fillable = [
        'staff_profile_id',
        'subject',
        'message',
        'initiated_by',
        'assigned_hr_id',
        'status',
        'deadline'
    ];

    public function staff()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_profile_id');
    }

    public function responses()
    {
        return $this->hasMany(HrQueryResponse::class);
    }

    public function hr()
    {
        return $this->belongsTo(User::class, 'assigned_hr_id');
    }
}
