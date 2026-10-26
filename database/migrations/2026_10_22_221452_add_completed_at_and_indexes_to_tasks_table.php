<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The tasks table was created outside this folder, so skip cleanly where it does not exist
        if (! Schema::hasTable('tasks')) {
            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }

            // Staff "my tasks" list and the per-status counts
            $table->index(['marketer_id', 'status'], 'tasks_assignee_status_index');
            // Overdue lookups and deadline ordering
            $table->index(['status', 'deadline'], 'tasks_status_deadline_index');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_assignee_status_index');
            $table->dropIndex('tasks_status_deadline_index');

            if (Schema::hasColumn('tasks', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });
    }
};
