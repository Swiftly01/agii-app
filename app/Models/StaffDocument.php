<?php

// app/Models/StaffDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_profile_id',
        'document_type',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'status',
        'issue_date',
        'expiry_date',
        'review_notes',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'reviewed_at' => 'datetime'
    ];

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Document type constants
    const TYPE_CERTIFICATE = 'certificate';
    const TYPE_TOR = 'tor';
    const TYPE_CONTRACT = 'contract';
    const TYPE_PAYSLIP = 'payslip';
    const TYPE_ID = 'identification';
    const TYPE_ACADEMIC = 'academic';
    const TYPE_PROFESSIONAL = 'professional';
    const TYPE_OTHER = 'other';

    public static function getDocumentTypes()
    {
        return [
            self::TYPE_CERTIFICATE => 'Certificate',
            self::TYPE_TOR => 'Terms of Reference',
            self::TYPE_CONTRACT => 'Employment Contract',
            self::TYPE_PAYSLIP => 'Payslip',
            self::TYPE_ID => 'ID Document',
            self::TYPE_ACADEMIC => 'Academic Document',
            self::TYPE_PROFESSIONAL => 'Professional Document',
            self::TYPE_OTHER => 'Other'
        ];
    }
}