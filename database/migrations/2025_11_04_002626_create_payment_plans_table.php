<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_payment_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPlansTable extends Migration
{
    public function up()
    {
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['regular', 'stores']);
            $table->enum('tier', ['basic', 'advance', 'premium']);
            $table->decimal('monthly_price', 10, 2);
            $table->decimal('yearly_price', 10, 2);
            $table->integer('product_limit')->nullable();
            $table->boolean('featured_listings')->default(false);
            $table->integer('featured_listings_count')->default(0);
            $table->enum('support_level', ['basic', 'priority', 'premium'])->default('basic');
            $table->boolean('analytics')->default(false);
            $table->boolean('custom_storefront')->default(false);
            $table->boolean('marketing_tools')->default(false);
            $table->enum('visibility', ['standard', 'enhanced', 'maximum'])->default('standard');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_plans');
    }
}
