<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('boost_expires_at')->nullable()->after('featured');
            $table->boolean('is_boost_carousel_pick')->default(false)->after('boost_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['boost_expires_at', 'is_boost_carousel_pick']);
        });
    }
};
