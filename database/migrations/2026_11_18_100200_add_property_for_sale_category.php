<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Safe to run on a database that already has the category
        if (DB::table('categories')->where('slug', 'property-for-sale')->exists()) {
            return;
        }

        DB::table('categories')->insert([
            'name' => 'Houses & Apartments for Sale',
            'slug' => 'property-for-sale',
            'type' => 'product',
            'description' => 'Houses and apartments for sale',
            'icon' => 'house-door',
            'specifications_template' => json_encode([]),
            'is_active' => true,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'property-for-sale')->delete();
    }
};
