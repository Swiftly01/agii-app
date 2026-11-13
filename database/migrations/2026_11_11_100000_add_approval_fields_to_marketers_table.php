<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Adds the review workflow to marketers, new sign-ups start pending and an admin approves or rejects them
    public function up(): void
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_active')->index();
            $table->text('application_note')->nullable()->after('notes');
            $table->text('rejection_reason')->nullable()->after('application_note');
        });

        // Marketers that already exist were trading before approval existed, so they stay approved
        DB::table('marketers')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'application_note', 'rejection_reason']);
        });
    }
};
