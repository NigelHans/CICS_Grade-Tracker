<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "  TEST ACCOUNTS VERIFICATION\n";
echo "========================================\n\n";

echo "📋 USERS IN DATABASE:\n";
$users = User::all();
foreach ($users as $user) {
    echo sprintf("  - %s (%s) [Role: %s]\n", $user->name, $user->email, $user->role);
}

echo "\n📚 COURSES:\n";
$courses = Course::all();
foreach ($courses as $course) {
    echo sprintf("  - %s: %s (Lecturer: %s)\n", $course->course_code, $course->course_title, $course->lecturer->name);
}

echo "\n✏️  ENROLLMENTS:\n";
$enrollments = Enrollment::with('student', 'course')->all();
foreach ($enrollments as $enrollment) {
    echo sprintf("  - %s enrolled in %s (Grade: %s, Expected: %s)\n", 
        $enrollment->student->name, 
        $enrollment->course->course_code, 
        $enrollment->grade,
        $enrollment->expected_grade
    );
}

echo "\n========================================\n";
echo "✅ Verification Complete!\n";
echo "========================================\n";
