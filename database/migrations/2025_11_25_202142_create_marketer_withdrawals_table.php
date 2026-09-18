<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_marketer_withdrawals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketer_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('marketer_id');
            $table->index('status');
            $table->index('processed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketer_withdrawals');
    }
};
