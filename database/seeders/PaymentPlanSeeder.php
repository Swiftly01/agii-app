<?php
// database/seeders/PaymentPlanSeeder.php

namespace Database\Seeders;

use App\Models\PaymentPlan;
use Illuminate\Database\Seeder;

class PaymentPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            // ==================== REGULAR PLANS ====================
            [
                'name' => 'Basic Regular',
                'type' => 'regular',
                'tier' => 'basic',
                'monthly_price' => 1000.00,
                'yearly_price' => 10200.00,
                'product_limit' => 10,
                'featured_listings' => false,
                'featured_listings_count' => 0,
                'support_level' => 'basic',
                'analytics' => false,
                'custom_storefront' => false,
                'marketing_tools' => false,
                'visibility' => 'standard',
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
                'description' => 'Perfect for individual sellers starting out',
            ],
            [
                'name' => 'Advance Regular',
                'type' => 'regular',
                'tier' => 'advance',
                'monthly_price' => 2000.00,
                'yearly_price' => 20400.00,
                'product_limit' => 50,
                'featured_listings' => true,
                'featured_listings_count' => 5,
                'support_level' => 'priority',
                'analytics' => true,
                'custom_storefront' => false,
                'marketing_tools' => false,
                'visibility' => 'enhanced',
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 2,
                'description' => 'Ideal for growing businesses with more products',
            ],
            [
                'name' => 'Premium Regular',
                'type' => 'regular',
                'tier' => 'premium',
                'monthly_price' => 3000.00,
                'yearly_price' => 30600.00,
                'product_limit' => null,
                'featured_listings' => true,
                'featured_listings_count' => 10,
                'support_level' => 'premium',
                'analytics' => true,
                'custom_storefront' => true,
                'marketing_tools' => true,
                'visibility' => 'maximum',
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
                'description' => 'Complete solution for established sellers',
            ],

            // ==================== STORES PLANS ====================
            [
                'name' => 'Basic Stores',
                'type' => 'stores',
                'tier' => 'basic',
                'monthly_price' => 2000.00,
                'yearly_price' => 20400.00,
                'product_limit' => 25,
                'featured_listings' => false,
                'featured_listings_count' => 0,
                'support_level' => 'basic',
                'analytics' => false,
                'custom_storefront' => false,
                'marketing_tools' => false,
                'visibility' => 'standard',
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 4,
                'description' => 'Essential features for small stores',
            ],
            [
                'name' => 'Advance Stores',
                'type' => 'stores',
                'tier' => 'advance',
                'monthly_price' => 3000.00,
                'yearly_price' => 30600.00,
                'product_limit' => 100,
                'featured_listings' => true,
                'featured_listings_count' => 10,
                'support_level' => 'priority',
                'analytics' => true,
                'custom_storefront' => true,
                'marketing_tools' => true,
                'visibility' => 'enhanced',
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 5,
                'description' => 'Advanced features for growing stores',
            ],
            [
                'name' => 'Premium Stores',
                'type' => 'stores',
                'tier' => 'premium',
                'monthly_price' => 5000.00,
                'yearly_price' => 51000.00,
                'product_limit' => null,
                'featured_listings' => true,
                'featured_listings_count' => 20,
                'support_level' => 'premium',
                'analytics' => true,
                'custom_storefront' => true,
                'marketing_tools' => true,
                'visibility' => 'maximum',
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 6,
                'description' => 'Enterprise-level features for large stores',
            ],
        ];

        foreach ($plans as $planData) {
            // Use updateOrCreate to avoid duplicates and handle existing records
            PaymentPlan::updateOrCreate(
                [
                    'name' => $planData['name'],
                    'type' => $planData['type'],
                    'tier' => $planData['tier']
                ],
                $planData
            );
        }

        $this->command->info('Payment plans seeded successfully!');
        $this->command->info('Total plans processed: ' . count($plans));

        // Display summary
        $this->displayPricingSummary();
    }

    protected function displayPricingSummary()
    {
        $this->command->info("\n" . 'VAT-Inclusive Pricing Summary:');
        $this->command->info('===============================');

        $plans = PaymentPlan::all();

        foreach ($plans as $plan) {
            $monthlyWithVat = $plan->getMonthlyPriceWithVat();
            $yearlyWithVat = $plan->getYearlyPriceWithVat();

            $this->command->info("{$plan->name}:");
            $this->command->info("  Monthly: ₦" . number_format($monthlyWithVat['total'], 2) . " (₦" . number_format($monthlyWithVat['subtotal'], 2) . " + ₦" . number_format($monthlyWithVat['vat_amount'], 2) . " VAT)");
            $this->command->info("  Yearly: ₦" . number_format($yearlyWithVat['total'], 2) . " (₦" . number_format($yearlyWithVat['subtotal'], 2) . " + ₦" . number_format($yearlyWithVat['vat_amount'], 2) . " VAT)");
            $this->command->info("");
        }

        $this->command->info("Total active plans: " . $plans->count());
    }
}
