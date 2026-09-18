<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // Product details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('old_price', 10, 2)->nullable();

            // Condition & Location
            $table->string('condition')->nullable(); // e.g. new, used
            $table->string('location')->nullable();

            // Inventory
            $table->integer('quantity')->default(0);

            // Media & Specifications
            $table->json('images')->nullable();
            $table->json('specifications')->nullable();

            // Reviews & Ratings
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('review_count')->default(0);

            // Status & Flags
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->boolean('featured')->default(false);
            $table->boolean('negotiable')->default(false);

            // SEO fields
            $table->json('tags')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('views')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
