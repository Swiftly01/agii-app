<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Polymorphic subject so any listing type can own a conversation
            $table->nullableMorphs('subject');
            $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete();

            // One conversation per buyer per listing
            $table->unique(['buyer_id', 'subject_type', 'subject_id'], 'conversations_buyer_subject_unique');
        });

        Schema::table('messages', function (Blueprint $table) {
            // Null until the recipient opens the thread
            $table->timestamp('read_at')->nullable();
            $table->index(['conversation_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['conversation_id', 'read_at']);
            $table->dropColumn('read_at');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropUnique('conversations_buyer_subject_unique');
            $table->dropConstrainedForeignId('buyer_id');
            $table->dropMorphs('subject');
        });
    }
};
