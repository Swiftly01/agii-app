<?php
// app/Models/Marketer.php

namespace App\Models;

use App\Enums\MarketerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marketer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'referral_code',
        'commission_rate',
        'total_earnings',
        'pending_earnings',
        'paid_earnings',
        'is_active',
        'approved_at',
        'approved_by',
        'notes',
        'status',
        'application_note',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'pending_earnings' => 'decimal:2',
        'paid_earnings' => 'decimal:2',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'status' => MarketerStatus::class,
    ];

    /**
     * Get the user that owns the marketer profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Admin who last approved or rejected this application
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved(): bool
    {
        return $this->status === MarketerStatus::Approved;
    }

    public function isPending(): bool
    {
        return $this->status === MarketerStatus::Pending;
    }

    public function isRejected(): bool
    {
        return $this->status === MarketerStatus::Rejected;
    }

    // users.referred_by stores the marketer's user id, so referrals join on user_id and not on the code
    /**
     * Get the vendors referred by this marketer.
     */
    public function referredVendors(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by', 'user_id')
            ->where('user_type', 'vendor');
    }

    /**
     * Get all referred users (both vendors and customers).
     */
    public function referredUsers(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by', 'user_id');
    }

    /**
     * Get the commission transactions for the marketer.
     */
    public function commissionTransactions(): HasMany
    {
        return $this->hasMany(CommissionTransaction::class);
    }

    /**
     * Get pending commission transactions.
     */
    public function pendingCommissions(): HasMany
    {
        return $this->commissionTransactions()->where('status', 'pending');
    }

    /**
     * Get paid commission transactions.
     */
    public function paidCommissions(): HasMany
    {
        return $this->commissionTransactions()->where('status', 'paid');
    }

    /**
     * Get the vendor subscriptions from referred vendors.
     */
    public function vendorSubscriptions(): HasMany
    {
        return $this->hasMany(VendorSubscription::class, 'referred_by', 'referral_code');
    }

    /**
     * Get active vendor subscriptions.
     */
    public function activeVendorSubscriptions(): HasMany
    {
        return $this->vendorSubscriptions()->where('status', 'active');
    }

    /**
     * Calculate total vendors count.
     */
    public function getTotalVendorsAttribute(): int
    {
        return $this->referredVendors()->count();
    }

    /**
     * Calculate active vendors count.
     */
    public function getActiveVendorsAttribute(): int
    {
        return $this->referredVendors()
            ->whereHas('activeSubscription')
            ->count();
    }

    /**
     * Calculate total products from referred vendors.
     */
    public function getTotalProductsAttribute(): int
    {
        return Product::whereIn('user_id', $this->referredVendors()->pluck('id'))
            ->where('status', 'active')
            ->count();
    }

    /**
     * Calculate total sales from referred vendors.
     */
    public function getTotalSalesAttribute(): float
    {
        return Product::whereIn('user_id', $this->referredVendors()->pluck('id'))
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get performance statistics.
     */
    public function getPerformanceStats(): array
    {
        return [
            'total_vendors' => $this->total_vendors,
            'active_vendors' => $this->active_vendors,
            'total_products' => $this->total_products,
            'total_sales' => $this->total_sales,
            'total_earnings' => $this->total_earnings,
            'pending_earnings' => $this->pending_earnings,
            'paid_earnings' => $this->paid_earnings,
            'commission_rate' => $this->commission_rate,
        ];
    }

    /**
     * Check if marketer can withdraw earnings.
     */
    public function canWithdraw(): bool
    {
        return $this->is_active && $this->pending_earnings > 0;
    }

    // Adds a commission to both the withdrawable balance and the lifetime total in one atomic query
    public function creditEarnings(float $amount): void
    {
        $this->incrementEach([
            'pending_earnings' => $amount,
            'total_earnings' => $amount,
        ]);
    }

    /**
     * Get available withdrawal amount.
     */
    public function getAvailableWithdrawalAmount(): float
    {
        return $this->pending_earnings;
    }

    /**
     * Generate a unique referral code.
     */
    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Find marketer by referral code.
     */
    public static function findByReferralCode(string $code): ?self
    {
        return self::where('referral_code', $code)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Scope active marketers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pending approval marketers.
     */
    public function scopePending($query)
    {
        return $query->where('is_active', false)->whereNull('approved_at');
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($marketer) {
            if (empty($marketer->referral_code)) {
                $marketer->referral_code = self::generateReferralCode();
            }
        });
    }
}
