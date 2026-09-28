<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vendor approval gate.
     *
     * `vendor_status` is null for accounts that have never applied to sell
     * (customers, marketers, admins). The moment a user upgrades to a
     * vendor (VendorUpgradeService::upgrade) it is set to 'pending', and an
     * admin then moves it to 'approved' or 'rejected'. Only 'approved'
     * vendors may create products (see EnsureVendorIsApproved middleware).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('vendor_status', ['pending', 'approved', 'rejected'])
                ->nullable()
                ->after('user_type')
                ->index();

            $table->timestamp('vendor_approved_at')->nullable()->after('vendor_status');

            $table->foreignId('vendor_approved_by')
                ->nullable()
                ->after('vendor_approved_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('vendor_rejection_reason')->nullable()->after('vendor_approved_by');
        });

        // Grandfather in vendors that were already trading under the old
        // no-approval flow so this migration doesn't lock out existing
        // sellers overnight. Only NEW vendor upgrades from this point on
        // start out 'pending' (see VendorUpgradeService::upgrade).
        DB::table('users')
            ->where('user_type', 'vendor')
            ->update([
                'vendor_status' => 'approved',
                'vendor_approved_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['vendor_approved_by']);
            $table->dropColumn([
                'vendor_status',
                'vendor_approved_at',
                'vendor_approved_by',
                'vendor_rejection_reason',
            ]);
        });
    }
};
