<?php
// app/Models/CommissionTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'marketer_id',
        'vendor_id',
        'subscription_id',
        'amount',
        'commission_rate',
        'commission_amount',
        'status',
        'paid_at',
        'payment_reference',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the marketer that owns the commission transaction.
     */
    public function marketer(): BelongsTo
    {
        return $this->belongsTo(Marketer::class);
    }

    /**
     * Get the vendor that the commission is for.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the subscription that generated the commission.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(VendorSubscription::class, 'subscription_id');
    }

    /**
     * Scope pending commissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope paid commissions.
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Mark commission as paid.
     */
    public function markAsPaid(string $paymentReference = null): bool
    {
        return $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'payment_reference' => $paymentReference,
        ]);
    }

    /**
     * Calculate commission amount.
     */
    public static function calculateCommission(float $amount, float $commissionRate): float
    {
        return $amount * ($commissionRate / 100);
    }
}
