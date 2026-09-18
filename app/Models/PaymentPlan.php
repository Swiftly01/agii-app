<?php
// app/Models/PaymentPlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'tier',
        'monthly_price',
        'yearly_price',
        'product_limit',
        'featured_listings',
        'featured_listings_count',
        'support_level',
        'analytics',
        'custom_storefront',
        'marketing_tools',
        'visibility',
        'is_active',
        'is_popular',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'featured_listings' => 'boolean',
        'analytics' => 'boolean',
        'custom_storefront' => 'boolean',
        'marketing_tools' => 'boolean',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(VendorSubscription::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByTier($query, $tier)
    {
        return $query->where('tier', $tier);
    }

    // Helper methods
    public function getPrice($billingCycle)
    {
        return $billingCycle === 'yearly' ? $this->yearly_price : $this->monthly_price;
    }

    public function getFormattedPrice($billingCycle)
    {
        $price = $this->getPrice($billingCycle);
        return '₦' . number_format($price, 2);
    }

    public function getYearlySavings()
    {
        $monthlyTotal = $this->monthly_price * 12;
        $yearlyPrice = $this->yearly_price;
        $savings = $monthlyTotal - $yearlyPrice;
        $savingsPercentage = ($savings / $monthlyTotal) * 100;

        return [
            'amount' => $savings,
            'percentage' => round($savingsPercentage)
        ];
    }


    // Add VAT calculation methods
    public function getPriceWithVat($billingCycle, $months = 1)
    {
        $basePrice = $this->getPrice($billingCycle);
        $subtotal = $billingCycle === 'yearly' ? $basePrice * ceil($months / 12) : $basePrice * $months;
        $vatAmount = $this->calculateVat($subtotal);

        return [
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'vat_rate' => 7.5, // 7.5% VAT
            'total' => $subtotal + $vatAmount,
            'total_kobo' => ($subtotal + $vatAmount) * 100,
        ];
    }

    public function calculateVat($amount)
    {
        $vatRate = 7.5; // 7.5%
        return ($amount * $vatRate) / 100;
    }

    public function getMonthlyPriceWithVat()
    {
        $vatAmount = $this->calculateVat($this->monthly_price);
        return [
            'subtotal' => $this->monthly_price,
            'vat_amount' => $vatAmount,
            'total' => $this->monthly_price + $vatAmount,
        ];
    }

    public function getYearlyPriceWithVat()
    {
        $vatAmount = $this->calculateVat($this->yearly_price);
        return [
            'subtotal' => $this->yearly_price,
            'vat_amount' => $vatAmount,
            'total' => $this->yearly_price + $vatAmount,
        ];
    }
}
