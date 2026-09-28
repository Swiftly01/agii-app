<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Product approval audit trail.
     *
     * We deliberately do NOT add a new 'rejected' value to the existing
     * `status` enum (active|inactive|pending) — widening an enum requires
     * a full table rebuild on some drivers and is easy to get wrong across
     * MySQL/SQLite. Instead: a rejected product is `status = inactive`
     * with `rejection_reason` set, which also keeps "rejected by admin"
     * and "disabled by vendor/admin" on the same, already-handled status
     * value everywhere products are queried by status.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('reviewed_at')->nullable()->after('status');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('reviewed_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('rejection_reason')->nullable()->after('reviewed_by');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['reviewed_at', 'reviewed_by', 'rejection_reason']);
            $table->dropIndex(['status']);
        });
    }
};
