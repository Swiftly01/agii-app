<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'product_id',
        'vendor_name',
        'vendor_contact_info',
        'product_name',
        'contact_date',
        'contact_method',
        'status',
        'deal_outcome',
        'deal_value',
        'notes',
        'vendor_rating',
        'outcome_reported_at'
    ];

    protected $casts = [
        'contact_date' => 'datetime',
        'outcome_reported_at' => 'datetime',
        'deal_value' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('deal_outcome', 'successful');
    }

    public function scopePendingOutcome($query)
    {
        return $query->whereNull('deal_outcome');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
