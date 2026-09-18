<?php
// app/Models/EmploymentApplication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'position_applying_for',
        'surname',
        'first_name',
        'other_name',
        'email',
        'sex',
        'date_of_birth',
        'state_of_origin',
        'lga',
        'marital_status',
        'educational_qualification',
        'residential_address',
        'residential_lga_state',
        'contact_number',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_contact',
        'next_of_kin_address',
        'parent_guardian_name',
        'parent_guardian_address',
        'declaration_signature',
        'declaration_date',
        'educational_qualifications',
        'professional_qualifications',
        'employment_history',
        'guarantors',
        'passport_photo',
        'resume',
        'certificates',
        'means_of_identification',
        'status',
        'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'declaration_date' => 'date',
        'educational_qualifications' => 'array',
        'professional_qualifications' => 'array',
        'employment_history' => 'array',
        'guarantors' => 'array',
        'certificates' => 'array',
        'means_of_identification' => 'array',
    ];

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return trim($this->surname . ' ' . $this->first_name . ' ' . ($this->other_name ?? ''));
    }

    // Scope for pending applications
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Get age from date of birth
    public function getAgeAttribute()
    {
        return now()->diffInYears($this->date_of_birth);
    }

    // Generate application reference
    public function getApplicationReferenceAttribute()
    {
        return 'EMP-' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($this->surname, 0, 3));
    }
}