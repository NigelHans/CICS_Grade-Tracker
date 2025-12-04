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
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'student_id')) {
                $table->foreignId('student_id')->constrained('users');
            }
            if (!Schema::hasColumn('enrollments', 'course_id')) {
                $table->foreignId('course_id')->constrained('courses');
            }
            if (!Schema::hasColumn('enrollments', 'grade')) {
                $table->decimal('grade', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('enrollments', 'current_grade')) {
                $table->decimal('current_grade', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('enrollments', 'expected_grade')) {
                $table->decimal('expected_grade', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('enrollments', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('enrollments', 'enrollment_date')) {
                $table->timestamp('enrollment_date')->useCurrent();
            }
            if (!Schema::hasColumn('enrollments', 'completion_date')) {
                $table->timestamp('completion_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
            $table->dropForeignIdFor('courses');
            $table->dropColumn([
                'student_id',
                'course_id',
                'grade',
                'current_grade',
                'expected_grade',
                'status',
                'enrollment_date',
                'completion_date',
            ]);
        });
    }
};
