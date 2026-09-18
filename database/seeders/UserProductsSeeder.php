<?php
// database/seeders/UserProductsSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\VendorSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserProductsSeeder extends Seeder
{  /*
    public function run()
    {
        // Get user with ID 1
        $user = User::find(1);

        if (!$user) {
            // Create user if doesn't exist
            $user = User::create([
                'first_name' => 'Vendor',
                'last_name' => 'User',
                'email' => 'vendor@agii.ng',
                'phone' => '+2348012345678',
                'password' => bcrypt('password'),
                'user_type' => 'vendor',
                'state' => 'Lagos',
                'city' => 'Lagos',
                'business_name' => 'Agii Vendor Store',
                'terms_accepted' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Check if user has active subscription
        $hasActiveSubscription = $user->hasActiveSubscription();

        if (!$hasActiveSubscription) {
            $this->command->info('User does not have active subscription. Creating demo subscription...');

            // Create an active subscription for the user
            VendorSubscription::create([
                'user_id' => $user->id,
                'payment_plan_id' => 1, // Assuming you have a basic plan
                'billing_cycle' => 'monthly',
                'months' => 12,
                'amount' => 12000.00,
                'subtotal' => 11162.79,
                'vat_amount' => 837.21,
                'vat_rate' => 7.5,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonths(12),
                'payment_reference' => 'DEMO_' . Str::random(10),
                'payment_method' => 'demo',
            ]);
        }

        // Create categories
        $categories = $this->createCategories();

        // Create products
        $this->createProducts($user, $categories);
    }

    private function createCategories()
    {
        $categories = [
            // Product Categories
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'type' => 'product',
                'description' => 'Electronic devices and gadgets',
            ],
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'type' => 'product',
                'description' => 'Cars, motorcycles and other vehicles',
            ],
            [
                'name' => 'Properties',
                'slug' => 'properties',
                'type' => 'product',
                'description' => 'Real estate properties',
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'type' => 'product',
                'description' => 'Clothing and accessories',
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'type' => 'product',
                'description' => 'Home furniture and garden items',
            ],

            // Service Categories
            [
                'name' => 'Ride Services',
                'slug' => 'ride-services',
                'type' => 'service',
                'description' => 'Transportation and ride services',
            ],
            [
                'name' => 'Professional Services',
                'slug' => 'professional-services',
                'type' => 'service',
                'description' => 'Professional and business services',
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            $createdCategories[strtolower($categoryData['name'])] = $category->id;
        }

        return $createdCategories;
    }

    private function createProducts($user, $categories)
    {
        $products = [
            // Electronics Products
            [
                'title' => 'iPhone 13 Pro Max',
                'description' => 'Latest iPhone 13 Pro Max in excellent condition. 256GB storage, never been repaired, with original box and accessories.',
                'price' => 450000.00,
                'condition' => 'Excellent',
                'location' => 'Lagos',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'storage' => '256GB',
                    'color' => 'Graphite',
                    'condition' => 'Excellent',
                    'warranty' => '3 months'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop'
                ],
                'rating' => 4.8,
                'review_count' => 24,
                'negotiable' => true,
                'tags' => ['iphone', 'smartphone', 'apple', 'mobile']
            ],
            [
                'title' => 'MacBook Pro M1',
                'description' => '2022 MacBook Pro with M1 chip. 16GB RAM, 512GB SSD. Perfect for professionals and students.',
                'price' => 750000.00,
                'condition' => 'Like New',
                'location' => 'Ikeja',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Apple M1',
                    'screen_size' => '13 inch'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 18,
                'negotiable' => false,
                'tags' => ['macbook', 'laptop', 'apple', 'm1']
            ],
            [
                'title' => 'Samsung Galaxy S21',
                'description' => 'Samsung Galaxy S21 128GB in like new condition. Comes with original charger and case.',
                'price' => 280000.00,
                'condition' => 'Like New',
                'location' => 'Port Harcourt',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'storage' => '128GB',
                    'color' => 'Phantom Gray',
                    'condition' => 'Like New',
                    'accessories' => 'Original charger and case'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400&h=300&fit=crop'
                ],
                'rating' => 4.6,
                'review_count' => 15,
                'negotiable' => true,
                'tags' => ['samsung', 'android', 'smartphone', 'galaxy']
            ],
            [
                'title' => 'PlayStation 5',
                'description' => 'Brand new PlayStation 5 disc version with 2 controllers. Still sealed in box.',
                'price' => 380000.00,
                'condition' => 'New',
                'location' => 'Surulere',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'version' => 'Disc Version',
                    'controllers' => '2',
                    'condition' => 'Brand New',
                    'warranty' => '1 year'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 32,
                'negotiable' => false,
                'tags' => ['playstation', 'gaming', 'console', 'ps5']
            ],
            [
                'title' => 'Smart TV 55inch',
                'description' => 'Samsung 55 inch 4K UHD Smart TV. Perfect for home entertainment with smart features.',
                'price' => 320000.00,
                'condition' => 'Excellent',
                'location' => 'Lagos',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'size' => '55 inch',
                    'resolution' => '4K UHD',
                    'brand' => 'Samsung',
                    'smart_features' => 'Yes'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=300&fit=crop'
                ],
                'rating' => 4.7,
                'review_count' => 12,
                'negotiable' => true,
                'tags' => ['tv', 'smart tv', 'samsung', '4k']
            ],
            [
                'title' => 'Wireless Headphones',
                'description' => 'Sony wireless headphones with noise cancellation. Perfect for music lovers and professionals.',
                'price' => 85000.00,
                'condition' => 'Good',
                'location' => 'Abuja',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'brand' => 'Sony',
                    'features' => 'Noise Cancelling',
                    'connectivity' => 'Wireless',
                    'battery_life' => '30 hours'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop'
                ],
                'rating' => 4.6,
                'review_count' => 8,
                'negotiable' => true,
                'tags' => ['headphones', 'sony', 'wireless', 'audio']
            ],

            // Vehicle Products
            [
                'title' => 'Toyota Camry 2018',
                'description' => '2018 Toyota Camry in excellent condition. Automatic transmission, low mileage, well maintained.',
                'price' => 8500000.00,
                'condition' => 'Excellent',
                'location' => 'Abuja',
                'category_id' => $categories['vehicles'],
                'specifications' => [
                    'year' => '2018',
                    'transmission' => 'Automatic',
                    'mileage' => '45,000km',
                    'fuel_type' => 'Petrol'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 7,
                'negotiable' => true,
                'tags' => ['toyota', 'camry', 'sedan', 'automatic']
            ],
            [
                'title' => 'Honda Civic 2015',
                'description' => '2015 Honda Civic manual transmission. Well maintained with service history available.',
                'price' => 4200000.00,
                'condition' => 'Good',
                'location' => 'Ibadan',
                'category_id' => $categories['vehicles'],
                'specifications' => [
                    'year' => '2015',
                    'transmission' => 'Manual',
                    'mileage' => '75,000km',
                    'fuel_type' => 'Petrol'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=400&h=300&fit=crop'
                ],
                'rating' => 4.5,
                'review_count' => 5,
                'negotiable' => true,
                'tags' => ['honda', 'civic', 'manual', 'sedan']
            ],
            [
                'title' => 'Mercedes Benz 2019',
                'description' => '2019 Mercedes Benz C-Class with low mileage. Luxury features and excellent condition.',
                'price' => 15000000.00,
                'condition' => 'Excellent',
                'location' => 'Abuja',
                'category_id' => $categories['vehicles'],
                'specifications' => [
                    'year' => '2019',
                    'model' => 'C-Class',
                    'mileage' => '30,000km',
                    'features' => 'Luxury Package'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 3,
                'negotiable' => false,
                'tags' => ['mercedes', 'benz', 'luxury', 'c-class']
            ],

            // Property Products
            [
                'title' => '3-Bedroom Apartment',
                'description' => 'Beautiful 3-bedroom apartment in Lekki Phase 1. Fully furnished with modern amenities.',
                'price' => 25000000.00,
                'condition' => 'New',
                'location' => 'Lekki',
                'category_id' => $categories['properties'],
                'specifications' => [
                    'bedrooms' => '3',
                    'bathrooms' => '3',
                    'furnishing' => 'Fully Furnished',
                    'location' => 'Lekki Phase 1'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=300&fit=crop'
                ],
                'rating' => 4.7,
                'review_count' => 9,
                'negotiable' => true,
                'tags' => ['apartment', 'lekki', '3-bedroom', 'furnished']
            ],
            [
                'title' => 'Office Space',
                'description' => 'Modern office space in Victoria Island. Perfect for businesses looking for premium location.',
                'price' => 500000.00,
                'condition' => 'Excellent',
                'location' => 'Victoria Island',
                'category_id' => $categories['properties'],
                'specifications' => [
                    'size' => '200sqm',
                    'type' => 'Office Space',
                    'location' => 'Victoria Island',
                    'lease_term' => 'Yearly'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=400&h=300&fit=crop'
                ],
                'rating' => 4.8,
                'review_count' => 6,
                'negotiable' => true,
                'tags' => ['office', 'victoria island', 'commercial', 'space']
            ],
            [
                'title' => 'Duplex for Sale',
                'description' => 'Spacious 5-bedroom duplex in GRA area. Perfect for large families with ample parking space.',
                'price' => 45000000.00,
                'condition' => 'Excellent',
                'location' => 'Benin',
                'category_id' => $categories['properties'],
                'specifications' => [
                    'bedrooms' => '5',
                    'bathrooms' => '5',
                    'type' => 'Duplex',
                    'location' => 'GRA'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=400&h=300&fit=crop'
                ],
                'rating' => 4.8,
                'review_count' => 4,
                'negotiable' => true,
                'tags' => ['duplex', 'gra', '5-bedroom', 'house']
            ],
        ];

        foreach ($products as $productData) {
            $slug = Str::slug($productData['title']);

            Product::create(array_merge($productData, [
                'user_id' => $user->id,
                'slug' => $slug,
                'status' => 'active',
                'quantity' => 1,
                'featured' => rand(0, 1),
            ]));
        }

        $this->command->info('Successfully created ' . count($products) . ' products for user ID: ' . $user->id);
    }

    */

        public function run()
    {
        // Get user with ID 1
        $user = User::find(1);

        if (!$user) {
            // Create user if doesn't exist
            $user = User::create([
                'first_name' => 'Vendor',
                'last_name' => 'User',
                'email' => 'vendor@agii.ng',
                'phone' => '+2348012345678',
                'password' => bcrypt('password'),
                'user_type' => 'vendor',
                'state' => 'Lagos',
                'city' => 'Lagos',
                'business_name' => 'Agii Vendor Store',
                'terms_accepted' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Check if user has active subscription
        $hasActiveSubscription = $user->hasActiveSubscription();

        if (!$hasActiveSubscription) {
            $this->command->info('User does not have active subscription. Creating demo subscription...');

            // Create an active subscription for the user
            VendorSubscription::create([
                'user_id' => $user->id,
                'payment_plan_id' => 1, // Assuming you have a basic plan
                'billing_cycle' => 'monthly',
                'months' => 12,
                'amount' => 12000.00,
                'subtotal' => 11162.79,
                'vat_amount' => 837.21,
                'vat_rate' => 7.5,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonths(12),
                'payment_reference' => 'DEMO_' . Str::random(10),
                'payment_method' => 'demo',
            ]);
        }

        // Create categories
        $categories = $this->createCategories();

        // Create products
        $this->createProducts($user, $categories);
    }

    private function createCategories()
    {
        $categories = [
            // Product Categories
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'type' => 'product',
                'description' => 'Electronic devices and gadgets',
            ],
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'type' => 'product',
                'description' => 'Cars, motorcycles and other vehicles',
            ],
            [
                'name' => 'Properties',
                'slug' => 'properties',
                'type' => 'product',
                'description' => 'Real estate properties',
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'type' => 'product',
                'description' => 'Clothing and accessories',
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'type' => 'product',
                'description' => 'Home furniture and garden items',
            ],

            // Service Categories
            [
                'name' => 'Ride Services',
                'slug' => 'ride-services',
                'type' => 'service',
                'description' => 'Transportation and ride services',
            ],
            [
                'name' => 'Professional Services',
                'slug' => 'professional-services',
                'type' => 'service',
                'description' => 'Professional and business services',
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(['slug' => $categoryData['slug']], $categoryData);
            $createdCategories[strtolower($categoryData['name'])] = $category->id;
        }

        return $createdCategories;
    }

    private function createProducts($user, $categories)
    {
        $products = [
            // Electronics Products
            [
                'title' => 'iPhone 13 Pro Max',
                'description' => 'Latest iPhone 13 Pro Max in excellent condition. 256GB storage, never been repaired, with original box and accessories.',
                'price' => 450000.00,
                'condition' => 'Excellent',
                'location' => 'Lagos',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'storage' => '256GB',
                    'color' => 'Graphite',
                    'condition' => 'Excellent',
                    'warranty' => '3 months'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=300&fit=crop'
                ],
                'rating' => 4.8,
                'review_count' => 24,
                'negotiable' => true,
                'tags' => ['iphone', 'smartphone', 'apple', 'mobile']
            ],
            [
                'title' => 'MacBook Pro M1',
                'description' => '2022 MacBook Pro with M1 chip. 16GB RAM, 512GB SSD. Perfect for professionals and students.',
                'price' => 750000.00,
                'condition' => 'Like New',
                'location' => 'Ikeja',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'ram' => '16GB',
                    'storage' => '512GB SSD',
                    'processor' => 'Apple M1',
                    'screen_size' => '13 inch'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 18,
                'negotiable' => false,
                'tags' => ['macbook', 'laptop', 'apple', 'm1']
            ],
            [
                'title' => 'Samsung Galaxy S21',
                'description' => 'Samsung Galaxy S21 128GB in like new condition. Comes with original charger and case.',
                'price' => 280000.00,
                'condition' => 'Like New',
                'location' => 'Port Harcourt',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'storage' => '128GB',
                    'color' => 'Phantom Gray',
                    'condition' => 'Like New',
                    'accessories' => 'Original charger and case'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400&h=300&fit=crop'
                ],
                'rating' => 4.6,
                'review_count' => 15,
                'negotiable' => true,
                'tags' => ['samsung', 'android', 'smartphone', 'galaxy']
            ],
            [
                'title' => 'PlayStation 5',
                'description' => 'Brand new PlayStation 5 disc version with 2 controllers. Still sealed in box.',
                'price' => 380000.00,
                'condition' => 'New',
                'location' => 'Surulere',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'version' => 'Disc Version',
                    'controllers' => '2',
                    'condition' => 'Brand New',
                    'warranty' => '1 year'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 32,
                'negotiable' => false,
                'tags' => ['playstation', 'gaming', 'console', 'ps5']
            ],
            [
                'title' => 'Smart TV 55inch',
                'description' => 'Samsung 55 inch 4K UHD Smart TV. Perfect for home entertainment with smart features.',
                'price' => 320000.00,
                'condition' => 'Excellent',
                'location' => 'Lagos',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'size' => '55 inch',
                    'resolution' => '4K UHD',
                    'brand' => 'Samsung',
                    'smart_features' => 'Yes'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=300&fit=crop'
                ],
                'rating' => 4.7,
                'review_count' => 12,
                'negotiable' => true,
                'tags' => ['tv', 'smart tv', 'samsung', '4k']
            ],
            [
                'title' => 'Wireless Headphones',
                'description' => 'Sony wireless headphones with noise cancellation. Perfect for music lovers and professionals.',
                'price' => 85000.00,
                'condition' => 'Good',
                'location' => 'Abuja',
                'category_id' => $categories['electronics'],
                'specifications' => [
                    'brand' => 'Sony',
                    'features' => 'Noise Cancelling',
                    'connectivity' => 'Wireless',
                    'battery_life' => '30 hours'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop'
                ],
                'rating' => 4.6,
                'review_count' => 8,
                'negotiable' => true,
                'tags' => ['headphones', 'sony', 'wireless', 'audio']
            ],

            // Vehicle Products
            [
                'title' => 'Toyota Camry 2018',
                'description' => '2018 Toyota Camry in excellent condition. Automatic transmission, low mileage, well maintained.',
                'price' => 8500000.00,
                'condition' => 'Excellent',
                'location' => 'Abuja',
                'category_id' => $categories['vehicles'],
                'specifications' => [
                    'year' => '2018',
                    'transmission' => 'Automatic',
                    'mileage' => '45,000km',
                    'fuel_type' => 'Petrol'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 7,
                'negotiable' => true,
                'tags' => ['toyota', 'camry', 'sedan', 'automatic']
            ],
            [
                'title' => 'Honda Civic 2015',
                'description' => '2015 Honda Civic manual transmission. Well maintained with service history available.',
                'price' => 4200000.00,
                'condition' => 'Good',
                'location' => 'Ibadan',
                'category_id' => $categories['vehicles'],
                'specifications' => [
                    'year' => '2015',
                    'transmission' => 'Manual',
                    'mileage' => '75,000km',
                    'fuel_type' => 'Petrol'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=400&h=300&fit=crop'
                ],
                'rating' => 4.5,
                'review_count' => 5,
                'negotiable' => true,
                'tags' => ['honda', 'civic', 'manual', 'sedan']
            ],
            [
                'title' => 'Mercedes Benz 2019',
                'description' => '2019 Mercedes Benz C-Class with low mileage. Luxury features and excellent condition.',
                'price' => 15000000.00,
                'condition' => 'Excellent',
                'location' => 'Abuja',
                'category_id' => $categories['vehicles'],
                'specifications' => [
                    'year' => '2019',
                    'model' => 'C-Class',
                    'mileage' => '30,000km',
                    'features' => 'Luxury Package'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400&h=300&fit=crop'
                ],
                'rating' => 4.9,
                'review_count' => 3,
                'negotiable' => false,
                'tags' => ['mercedes', 'benz', 'luxury', 'c-class']
            ],

            // Property Products
            [
                'title' => '3-Bedroom Apartment',
                'description' => 'Beautiful 3-bedroom apartment in Lekki Phase 1. Fully furnished with modern amenities.',
                'price' => 25000000.00,
                'condition' => 'New',
                'location' => 'Lekki',
                'category_id' => $categories['properties'],
                'specifications' => [
                    'bedrooms' => '3',
                    'bathrooms' => '3',
                    'furnishing' => 'Fully Furnished',
                    'location' => 'Lekki Phase 1'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=300&fit=crop'
                ],
                'rating' => 4.7,
                'review_count' => 9,
                'negotiable' => true,
                'tags' => ['apartment', 'lekki', '3-bedroom', 'furnished']
            ],
            [
                'title' => 'Office Space',
                'description' => 'Modern office space in Victoria Island. Perfect for businesses looking for premium location.',
                'price' => 500000.00,
                'condition' => 'Excellent',
                'location' => 'Victoria Island',
                'category_id' => $categories['properties'],
                'specifications' => [
                    'size' => '200sqm',
                    'type' => 'Office Space',
                    'location' => 'Victoria Island',
                    'lease_term' => 'Yearly'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=400&h=300&fit=crop'
                ],
                'rating' => 4.8,
                'review_count' => 6,
                'negotiable' => true,
                'tags' => ['office', 'victoria island', 'commercial', 'space']
            ],
            [
                'title' => 'Duplex for Sale',
                'description' => 'Spacious 5-bedroom duplex in GRA area. Perfect for large families with ample parking space.',
                'price' => 45000000.00,
                'condition' => 'Excellent',
                'location' => 'Benin',
                'category_id' => $categories['properties'],
                'specifications' => [
                    'bedrooms' => '5',
                    'bathrooms' => '5',
                    'type' => 'Duplex',
                    'location' => 'GRA'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=400&h=300&fit=crop'
                ],
                'rating' => 4.8,
                'review_count' => 4,
                'negotiable' => true,
                'tags' => ['duplex', 'gra', '5-bedroom', 'house']
            ],
        ];

        foreach ($products as $productData) {
            $slug = Str::slug($productData['title']);

            Product::firstOrCreate(
                ['slug' => $slug],
                array_merge($productData, [
                    'user_id' => $user->id,
                    'slug' => $slug,
                    'status' => 'active',
                    'quantity' => 1,
                    'featured' => rand(0, 1),
                ])
            );
        }

        $this->command->info('Successfully created ' . count($products) . ' products for user ID: ' . $user->id);
    }
}
