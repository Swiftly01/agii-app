<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_marketers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('referral_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->decimal('pending_earnings', 10, 2)->default(0.00);
            $table->decimal('paid_earnings', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('referral_code');
            $table->index('is_active');
            $table->index('approved_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketers');
    }
};
