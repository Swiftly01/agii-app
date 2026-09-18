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
        // Update your users migration file
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();

            $table->string('phone');
            $table->string('password');
            $table->enum('user_type', ['customer', 'vendor'])->default('customer');

            // Location fields (for both customer and vendor)
            $table->string('state')->nullable();
            $table->string('local_government')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();

            // Vendor-specific fields
            $table->string('business_name')->nullable();
            $table->enum('business_type', ['individual', 'company'])->nullable();
            $table->string('business_category')->nullable();

            // Social media fields
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('whatsapp_number')->nullable();

            // Referral system
            $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users');

            // Customer interests (store as JSON)
            $table->json('interests')->nullable();

            // Preferences
            $table->boolean('newsletter_subscribed')->default(false);
            $table->boolean('terms_accepted')->default(false);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Create referrals table (optional for tracking)
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users');
            $table->foreignId('referred_id')->constrained('users');
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->boolean('is_claimed')->default(false);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};