<?php

namespace App\Models;

use App\Enums\SpotlightPaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// One Paystack charge for one month of hero spotlight
class HeroSpotlightPayment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'status',
        'reference',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => SpotlightPaymentStatus::class,
        'payment_details' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Paystack works in kobo
    public function amountInKobo(): int
    {
        return (int) round($this->amount * 100);
    }
}
