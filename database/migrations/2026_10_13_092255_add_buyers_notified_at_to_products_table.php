<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('buyers_notified_at')->nullable()->after('reviewed_at');
        });

        DB::table('products')
            ->where('status', 'active')
            ->update(['buyers_notified_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('buyers_notified_at');
        });
    }
};
