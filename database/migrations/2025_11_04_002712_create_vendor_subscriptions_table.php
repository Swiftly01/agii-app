<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_vendor_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_plan_id')->constrained()->onDelete('cascade');
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->integer('months')->default(1);
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, active, expired, cancelled
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_subscriptions');
    }
}
