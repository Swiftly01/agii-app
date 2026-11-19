<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Land & Plots for Sale is a Real Estate subcategory so it shows in the Real Estate submenu
    public function up(): void
    {
        // Safe to run on a database that already has the category
        if (DB::table('categories')->where('slug', 'land-plots-for-sale')->exists()) {
            return;
        }

        DB::table('categories')->insert([
            'name' => 'Land & Plots for Sale',
            'slug' => 'land-plots-for-sale',
            'type' => 'product',
            'description' => 'Land and plots for sale',
            'icon' => 'map',
            'specifications_template' => json_encode([]),
            'is_active' => true,
            'parent_id' => DB::table('categories')->where('slug', 'real-estate')->value('id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'land-plots-for-sale')->delete();
    }
};
