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
        // Create multiple lecturers
        $lecturers = [
            [
                'name' => 'Princess Melo',
                'email' => 'princessmelo@g.batstate-u.edu.ph',
                'sr_code' => 'PROF001'
            ],
            [
                'name' => 'Dr. Robert Johnson',
                'email' => 'robert.johnson@g.batstate-u.edu.ph',
                'sr_code' => 'PROF002'
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@g.batstate-u.edu.ph',
                'sr_code' => 'PROF003'
            ],
            [
                'name' => 'Prof. Michael Chen',
                'email' => 'michael.chen@g.batstate-u.edu.ph',
                'sr_code' => 'PROF004'
            ]
        ];

        $createdLecturers = [];
        foreach ($lecturers as $lecturerData) {
            $lecturer = User::firstOrCreate(
                ['email' => $lecturerData['email']],
                [
                    'name' => $lecturerData['name'],
                    'email' => $lecturerData['email'],
                    'password' => Hash::make('password123'),
                    'sr_code' => $lecturerData['sr_code'],
                    'role' => 'lecturer'
                ]
            );
            $createdLecturers[] = $lecturer;
        }

        // Create multiple students with varying performance
        $studentData = [
            ['name' => 'Juan Carlos Santos', 'email' => '23-07848@g.batstate-u.edu.ph', 'student_id' => '23-07848', 'year' => 3],
            ['name' => 'Maria De Los Santos', 'email' => '23-07849@g.batstate-u.edu.ph', 'student_id' => '23-07849', 'year' => 1],
            ['name' => 'Angelo Reyes', 'email' => '23-07850@g.batstate-u.edu.ph', 'student_id' => '23-07850', 'year' => 2],
            ['name' => 'Sofia Rodriguez', 'email' => '23-07851@g.batstate-u.edu.ph', 'student_id' => '23-07851', 'year' => 2],
            ['name' => 'Carlos Mendoza', 'email' => '23-07852@g.batstate-u.edu.ph', 'student_id' => '23-07852', 'year' => 3],
            ['name' => 'Isabella Cruz', 'email' => '23-07853@g.batstate-u.edu.ph', 'student_id' => '23-07853', 'year' => 3],
            ['name' => 'Miguel Torres', 'email' => '23-07854@g.batstate-u.edu.ph', 'student_id' => '23-07854', 'year' => 1],
            ['name' => 'Lucia Fernandez', 'email' => '23-07855@g.batstate-u.edu.ph', 'student_id' => '23-07855', 'year' => 2],
            ['name' => 'Diego Ruiz', 'email' => '23-07856@g.batstate-u.edu.ph', 'student_id' => '23-07856', 'year' => 3],
            ['name' => 'Valentina Morales', 'email' => '23-07857@g.batstate-u.edu.ph', 'student_id' => '23-07857', 'year' => 4],
            ['name' => 'Rafael Silva', 'email' => '23-07858@g.batstate-u.edu.ph', 'student_id' => '23-07858', 'year' => 4],
            ['name' => 'Camila Gutierrez', 'email' => '23-07859@g.batstate-u.edu.ph', 'student_id' => '23-07859', 'year' => 2],
        ];

        $students = [];
        foreach ($studentData as $data) {
            $student = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password123'),
                    'sr_code' => $data['student_id'],
                    'year_level' => $data['year'],
                    'role' => 'student'
                ]
            );
            $students[] = $student;
        }

        // Create sample courses - 8 courses per year level (32 total)
        $coursesData = [
            // Year 1 Courses (100-level)
            ['code' => 'CS101', 'title' => 'Introduction to Computer Science', 'credits' => 3, 'lecturer_idx' => 0, 'room' => 'A-101', 'year' => 1],
            ['code' => 'CS102', 'title' => 'Programming Fundamentals', 'credits' => 4, 'lecturer_idx' => 0, 'room' => 'A-102', 'year' => 1],
            ['code' => 'CS103', 'title' => 'Digital Logic', 'credits' => 3, 'lecturer_idx' => 1, 'room' => 'A-103', 'year' => 1],
            ['code' => 'CS104', 'title' => 'Web Design Basics', 'credits' => 3, 'lecturer_idx' => 1, 'room' => 'A-104', 'year' => 1],
            ['code' => 'MATH101', 'title' => 'Calculus I', 'credits' => 4, 'lecturer_idx' => 3, 'room' => 'D-101', 'year' => 1],
            ['code' => 'MATH102', 'title' => 'Linear Algebra', 'credits' => 3, 'lecturer_idx' => 3, 'room' => 'D-102', 'year' => 1],
            ['code' => 'ENG101', 'title' => 'English Composition', 'credits' => 3, 'lecturer_idx' => 0, 'room' => 'A-105', 'year' => 1],
            ['code' => 'PHY101', 'title' => 'General Physics I', 'credits' => 4, 'lecturer_idx' => 2, 'room' => 'B-101', 'year' => 1],
            
            // Year 2 Courses (200-level)
            ['code' => 'CS201', 'title' => 'Data Structures', 'credits' => 4, 'lecturer_idx' => 1, 'room' => 'B-201', 'year' => 2],
            ['code' => 'CS202', 'title' => 'Database Management Systems', 'credits' => 3, 'lecturer_idx' => 1, 'room' => 'B-202', 'year' => 2],
            ['code' => 'CS203', 'title' => 'Computer Architecture', 'credits' => 3, 'lecturer_idx' => 2, 'room' => 'B-203', 'year' => 2],
            ['code' => 'CS204', 'title' => 'Operating Systems', 'credits' => 4, 'lecturer_idx' => 2, 'room' => 'B-204', 'year' => 2],
            ['code' => 'MATH201', 'title' => 'Calculus II', 'credits' => 4, 'lecturer_idx' => 3, 'room' => 'D-201', 'year' => 2],
            ['code' => 'MATH202', 'title' => 'Discrete Mathematics', 'credits' => 3, 'lecturer_idx' => 3, 'room' => 'D-202', 'year' => 2],
            ['code' => 'ENG201', 'title' => 'Technical Writing', 'credits' => 3, 'lecturer_idx' => 0, 'room' => 'A-201', 'year' => 2],
            ['code' => 'PHY201', 'title' => 'General Physics II', 'credits' => 4, 'lecturer_idx' => 2, 'room' => 'B-301', 'year' => 2],
            
            // Year 3 Courses (300-level)
            ['code' => 'CS301', 'title' => 'Web Development', 'credits' => 3, 'lecturer_idx' => 2, 'room' => 'C-301', 'year' => 3],
            ['code' => 'CS302', 'title' => 'Software Engineering', 'credits' => 4, 'lecturer_idx' => 2, 'room' => 'C-302', 'year' => 3],
            ['code' => 'CS303', 'title' => 'Algorithms', 'credits' => 3, 'lecturer_idx' => 1, 'room' => 'C-303', 'year' => 3],
            ['code' => 'CS304', 'title' => 'Mobile App Development', 'credits' => 4, 'lecturer_idx' => 0, 'room' => 'C-304', 'year' => 3],
            ['code' => 'CS305', 'title' => 'Network Security', 'credits' => 3, 'lecturer_idx' => 1, 'room' => 'C-305', 'year' => 3],
            ['code' => 'CS306', 'title' => 'Artificial Intelligence Basics', 'credits' => 4, 'lecturer_idx' => 3, 'room' => 'C-306', 'year' => 3],
            ['code' => 'ENG301', 'title' => 'Professional Communication', 'credits' => 3, 'lecturer_idx' => 0, 'room' => 'A-301', 'year' => 3],
            ['code' => 'STAT301', 'title' => 'Probability and Statistics', 'credits' => 3, 'lecturer_idx' => 3, 'room' => 'D-303', 'year' => 3],
            
            // Year 4 Courses (400-level)
            ['code' => 'CS401', 'title' => 'Advanced Web Technologies', 'credits' => 3, 'lecturer_idx' => 2, 'room' => 'D-401', 'year' => 4],
            ['code' => 'CS402', 'title' => 'Cloud Computing', 'credits' => 4, 'lecturer_idx' => 1, 'room' => 'D-402', 'year' => 4],
            ['code' => 'CS403', 'title' => 'Machine Learning', 'credits' => 4, 'lecturer_idx' => 3, 'room' => 'D-403', 'year' => 4],
            ['code' => 'CS404', 'title' => 'Cybersecurity', 'credits' => 3, 'lecturer_idx' => 2, 'room' => 'D-404', 'year' => 4],
            ['code' => 'CS405', 'title' => 'Capstone Project I', 'credits' => 4, 'lecturer_idx' => 0, 'room' => 'D-405', 'year' => 4],
            ['code' => 'CS406', 'title' => 'Capstone Project II', 'credits' => 4, 'lecturer_idx' => 1, 'room' => 'D-406', 'year' => 4],
            ['code' => 'MGMT401', 'title' => 'IT Project Management', 'credits' => 3, 'lecturer_idx' => 0, 'room' => 'D-407', 'year' => 4],
            ['code' => 'ETHIC401', 'title' => 'IT Ethics and Society', 'credits' => 3, 'lecturer_idx' => 3, 'room' => 'D-408', 'year' => 4],
        ];

        $courses = [];
        foreach ($coursesData as $data) {
            $course = Course::firstOrCreate(
                ['course_code' => $data['code']],
                [
                    'course_code' => $data['code'],
                    'course_title' => $data['title'],
                    'credits' => $data['credits'],
                    'semester' => 'Fall 2025',
                    'instructor' => $createdLecturers[$data['lecturer_idx']]->name,
                    'room' => $data['room'],
                    'lecturer_id' => $createdLecturers[$data['lecturer_idx']]->id,
                    'syllabus' => json_encode([
                        ['name' => 'Attendance', 'percentage' => 10, 'max_points' => 10],
                        ['name' => 'Participation', 'percentage' => 20, 'max_points' => 20],
                        ['name' => 'Assignments', 'percentage' => 20, 'max_points' => 40],
                        ['name' => 'Midterm Exam', 'percentage' => 25, 'max_points' => 100],
                        ['name' => 'Final Exam', 'percentage' => 25, 'max_points' => 100],
                    ])
                ]
            );
            $courses[] = $course;
        }

        // Enroll students in courses with varying grades
        $gradeRanges = [
            [95, 100], // Excellent
            [85, 94],  // Good
            [75, 84],  // Average
            [65, 74],  // Below Average
            [50, 64],  // Failing
        ];

        foreach ($students as $index => $student) {
            $studentYear = $studentData[$index]['year'];
            
            // Enroll student based on their year level
            // Year 1: All 8 year 1 courses (active)
            // Year 2: All 8 year 1 courses (completed) + All 8 year 2 courses (active)
            // Year 3: All previous 16 (completed) + All 8 year 3 courses (active)
            // Year 4: All previous 24 (completed) + All 8 year 4 courses (active)
            
            foreach ($courses as $courseIdx => $course) {
                $courseYear = $course->year;
                $shouldTake = false;
                $status = 'active';
                
                // Determine if student should take this course
                if ($courseYear < $studentYear) {
                    // Lower year courses: Already completed
                    $shouldTake = true;
                    $status = 'completed';
                } elseif ($courseYear == $studentYear) {
                    // Current year courses: Active
                    $shouldTake = true;
                    $status = 'active';
                }
                // If courseYear > studentYear, don't take it
                
                if ($shouldTake) {
                    // Vary grades by student performance level
                    $gradeRange = $gradeRanges[$index % count($gradeRanges)];
                    $grade = rand($gradeRange[0], $gradeRange[1]);
                    
                    // Adjust grades slightly for completed courses (usually better or same)
                    if ($status === 'completed') {
                        $grade = min(100, $grade + rand(0, 5));
                    }

                    Enrollment::firstOrCreate(
                        ['student_id' => $student->id, 'course_id' => $course->id],
                        [
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'grade' => $grade,
                            'expected_grade' => max($grade - 5, 0),
                            'status' => $status,
                            'enrollment_date' => $status === 'completed' ? now()->subMonths(rand(3, 12)) : now(),
                        ]
                    );
                }
            }
        }

        $this->command->info('Test accounts created successfully!');
        $this->command->info('');
        $this->command->info('STUDENT ACCOUNTS (Password: password123):');
        foreach ($studentData as $student) {
            $this->command->info('  - ' . $student['email']);
        }
        $this->command->info('');
        $this->command->info('LECTURER ACCOUNTS (Password: password123):');
        foreach ($lecturers as $lecturer) {
            $this->command->info('  - ' . $lecturer['email']);
        }
    }
}
