<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_commission_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commission_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained('marketers')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('vendor_subscriptions')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('marketer_id');
            $table->index('vendor_id');
            $table->index('subscription_id');
            $table->index('status');
            $table->index('paid_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_transactions');
    }
};
