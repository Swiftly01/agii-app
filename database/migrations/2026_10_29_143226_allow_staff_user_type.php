<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // user_type was created as enum('customer','vendor'). Admin and marketer
        // values are already in use, so make it a plain string to accept 'staff'.
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type', 20)->default('customer')->change();
        });

        // Staff created through the employment flow were saved as 'marketer'.
        // Move them to 'staff' so login sends them to the staff dashboard.
        DB::table('users')
            ->where('user_type', 'marketer')
            ->whereIn('id', DB::table('staff_profiles')->whereNull('deleted_at')->select('user_id'))
            ->update(['user_type' => 'staff']);
    }

    public function down(): void
    {
        // Intentionally left as-is: narrowing the column back to an enum could fail on existing data.
    }
};
