<?php
// app/Models/VendorSubscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorSubscription extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'payment_plan_id',
        'billing_cycle',
        'months',
        'amount', // Total amount including VAT
        'subtotal', // Amount before VAT
        'vat_amount', // VAT amount
        'vat_rate', // VAT rate (default 7.5%)
        'status',
        'starts_at',
        'expires_at',
        'payment_reference',
        'payment_method',
        'payment_details',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_details' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    public function calculateTotalAmount($months = 1)
    {
        $plan = $this->paymentPlan;
        $basePrice = $this->billing_cycle === 'yearly' ? $plan->yearly_price : $plan->monthly_price;

        if ($this->billing_cycle === 'yearly') {
            return $basePrice * ceil($months / 12);
        }

        return $basePrice * $months;
    }

    public function getExpiryDate()
    {
        if ($this->billing_cycle === 'yearly') {
            return $this->starts_at->addYears(ceil($this->months / 12));
        }

        return $this->starts_at->addMonths($this->months);
    }

    public function scopeByPlan($query, $planIds)
    {
        if (!is_array($planIds)) {
            $planIds = [$planIds];
        }
        return $query->whereIn('payment_plan_id', $planIds);
    }
    public function getPlanNameAttribute()
    {
        return $this->paymentPlan?->name;
    }
}
