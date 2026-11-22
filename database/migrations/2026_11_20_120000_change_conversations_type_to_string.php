<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Existing private/group values are kept as they are
            $table->string('type', 20)->default('private')->change();
        });
    }

    public function down(): void
    {
        // Listing threads cannot exist in the old enum
        DB::table('conversations')->where('type', 'listing')->delete();

        Schema::table('conversations', function (Blueprint $table) {
            $table->enum('type', ['private', 'group'])->default('private')->change();
        });
    }
};