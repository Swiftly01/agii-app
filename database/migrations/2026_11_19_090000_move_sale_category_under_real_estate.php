<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // The sale form becomes a subcategory of Real Estate so it shows in the Real Estate submenu
    public function up(): void
    {
        $parentId = DB::table('categories')->where('slug', 'real-estate')->value('id');

        if ($parentId) {
            DB::table('categories')->where('slug', 'property-for-sale')->update(['parent_id' => $parentId]);
        }
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'property-for-sale')->update(['parent_id' => null]);
    }
};
