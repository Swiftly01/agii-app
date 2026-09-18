<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('vendor_name');
            $table->string('vendor_contact_info'); // email, phone, etc.
            $table->string('product_name');
            $table->dateTime('contact_date');
            $table->string('contact_method')->default('website'); // website, email, phone, etc.
            $table->string('status')->default('contacted'); // contacted, pending_followup, completed
            $table->string('deal_outcome')->nullable(); // successful, unsuccessful, negotiating, cancelled, pending_payment, delivered
            $table->decimal('deal_value', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('vendor_rating')->nullable(); // positive, neutral, negative
            $table->timestamp('outcome_reported_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'contact_date']);
            $table->index(['vendor_id', 'status']);
            $table->index('deal_outcome');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_contacts');
    }
};
