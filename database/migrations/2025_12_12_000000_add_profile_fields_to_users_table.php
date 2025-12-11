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
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id')->nullable()->after('sr_code');
            $table->string('phone')->nullable()->after('student_id');
            $table->string('department')->nullable()->after('phone');
            $table->string('year_level')->nullable()->after('department');
            $table->string('program')->nullable()->after('year_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'phone', 'department', 'year_level', 'program']);
        });
    }
};
