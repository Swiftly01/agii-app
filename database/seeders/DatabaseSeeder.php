<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PaymentPlanSeeder::class,
            CategorySeeder::class,
            UserProductsSeeder::class,
            
            // Add other seeders here
        ]);
    }
}
