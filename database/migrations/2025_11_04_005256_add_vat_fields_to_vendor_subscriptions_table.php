<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_vat_fields_to_vendor_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVatFieldsToVendorSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('vendor_subscriptions', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->after('amount')->nullable();
            $table->decimal('vat_amount', 10, 2)->after('subtotal')->nullable();
            $table->decimal('vat_rate', 5, 2)->after('vat_amount')->default(7.5);
        });
    }

    public function down()
    {
        Schema::table('vendor_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_amount', 'vat_rate']);
        });
    }
}
