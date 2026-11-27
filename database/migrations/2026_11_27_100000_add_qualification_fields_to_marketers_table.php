<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Nullable so marketers who signed up before these fields existed stay valid
    public function up(): void
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->unsignedTinyInteger('years_of_experience')->nullable()->after('application_note');
            $table->string('education_level', 20)->nullable()->after('years_of_experience');
            $table->string('certificate_path')->nullable()->after('education_level');
        });
    }

    public function down(): void
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->dropColumn(['years_of_experience', 'education_level', 'certificate_path']);
        });
    }
};
