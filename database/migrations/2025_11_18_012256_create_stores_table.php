<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_stores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('store_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('banner')->nullable();
            $table->string('logo')->nullable();
            $table->string('store_phone')->nullable(); // Different from user phone
            $table->string('store_email')->nullable(); // Different from user email
            $table->text('store_address')->nullable(); // Different from user address
            $table->text('return_policy')->nullable();
            $table->text('shipping_policy')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
