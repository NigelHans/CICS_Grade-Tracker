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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'course_code')) {
                $table->string('course_code')->unique();
            }
            if (!Schema::hasColumn('courses', 'course_title')) {
                $table->string('course_title');
            }
            if (!Schema::hasColumn('courses', 'credits')) {
                $table->integer('credits')->default(3);
            }
            if (!Schema::hasColumn('courses', 'semester')) {
                $table->string('semester')->nullable();
            }
            if (!Schema::hasColumn('courses', 'instructor')) {
                $table->string('instructor')->nullable();
            }
            if (!Schema::hasColumn('courses', 'room')) {
                $table->string('room')->nullable();
            }
            if (!Schema::hasColumn('courses', 'lecturer_id')) {
                $table->foreignId('lecturer_id')->constrained('users');
            }
            if (!Schema::hasColumn('courses', 'syllabus')) {
                $table->json('syllabus')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
            $table->dropColumn([
                'course_code',
                'course_title',
                'credits',
                'semester',
                'instructor',
                'room',
                'lecturer_id',
                'syllabus',
            ]);
        });
    }
};
