<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test student
        $student = User::firstOrCreate(
            ['email' => '23-07848@g.batstate-u.edu.ph'],
            [
                'name' => 'Juan Carlos Santos',
                'email' => '23-07848@g.batstate-u.edu.ph',
                'password' => Hash::make('password123'),
                'sr_code' => '23-07848',
                'role' => 'student'
            ]
        );

        // Create a test lecturer
        $lecturer = User::firstOrCreate(
            ['email' => 'princessmelo@g.batstate-u.edu.ph'],
            [
                'name' => 'Princess Melo',
                'email' => 'princessmelo@g.batstate-u.edu.ph',
                'password' => Hash::make('password123'),
                'sr_code' => 'PROF001',
                'role' => 'lecturer'
            ]
        );

        // Create sample courses for the lecturer
        $course1 = Course::firstOrCreate(
            ['course_code' => 'CS101'],
            [
                'course_code' => 'CS101',
                'course_title' => 'Introduction to Computer Science',
                'credits' => 3,
                'semester' => 'Fall 2025',
                'instructor' => $lecturer->name,
                'room' => 'A-101',
                'lecturer_id' => $lecturer->id,
                'syllabus' => json_encode([
                    ['name' => 'Attendance', 'percentage' => 10, 'max_points' => 10],
                    ['name' => 'Participation', 'percentage' => 20, 'max_points' => 20],
                    ['name' => 'Midterm Exam', 'percentage' => 30, 'max_points' => 100],
                    ['name' => 'Final Project', 'percentage' => 20, 'max_points' => 50],
                    ['name' => 'Final Exam', 'percentage' => 20, 'max_points' => 100],
                ])
            ]
        );

        $course2 = Course::firstOrCreate(
            ['course_code' => 'CS102'],
            [
                'course_code' => 'CS102',
                'course_title' => 'Programming Fundamentals',
                'credits' => 4,
                'semester' => 'Fall 2025',
                'instructor' => $lecturer->name,
                'room' => 'A-102',
                'lecturer_id' => $lecturer->id,
                'syllabus' => json_encode([
                    ['name' => 'Quizzes', 'percentage' => 15, 'max_points' => 60],
                    ['name' => 'Assignments', 'percentage' => 25, 'max_points' => 40],
                    ['name' => 'Midterm Exam', 'percentage' => 25, 'max_points' => 100],
                    ['name' => 'Final Project', 'percentage' => 35, 'max_points' => 150],
                ])
            ]
        );

        // Enroll the student in both courses
        Enrollment::firstOrCreate(
            ['student_id' => $student->id, 'course_id' => $course1->id],
            [
                'student_id' => $student->id,
                'course_id' => $course1->id,
                'grade' => 87.5,
                'expected_grade' => 85,
                'status' => 'active',
                'enrollment_date' => now(),
            ]
        );

        Enrollment::firstOrCreate(
            ['student_id' => $student->id, 'course_id' => $course2->id],
            [
                'student_id' => $student->id,
                'course_id' => $course2->id,
                'grade' => 92.0,
                'expected_grade' => 90,
                'status' => 'active',
                'enrollment_date' => now(),
            ]
        );

        $this->command->info('Test accounts created successfully!');
        $this->command->info('Student Email: 23-07848@g.batstate-u.edu.ph (Password: password123)');
        $this->command->info('Lecturer Email: princessmelo@g.batstate-u.edu.ph (Password: password123)');
    }
}
