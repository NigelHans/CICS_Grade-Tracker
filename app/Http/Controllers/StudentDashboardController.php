<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;

class StudentDashboardController extends Controller
{
    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()
            ->with('course')
            ->limit(9)
            ->get();

        return view('student.dashboard', compact('student', 'enrollments'));
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $student = Auth::user();
        
        return view('student.profile', compact('student'));
    }

    /**
     * Show student grades
     */
    public function grades()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()
            ->with('course')
            ->get();

        // Calculate statistics
        $totalCourses = $enrollments->count();
        $averageGrade = $enrollments->avg('grade') ?? 0;
        $passedCourses = $enrollments->where('grade', '>=', 60)->count();
        $failedCourses = $enrollments->where('grade', '<', 60)->count();

        return view('student.grades', compact(
            'student',
            'enrollments',
            'totalCourses',
            'averageGrade',
            'passedCourses',
            'failedCourses'
        ));
    }

    /**
     * Show student enrolled courses
     */
    public function courses()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()
            ->with('course')
            ->get();

        // Group by semester
        $coursesBySemester = $enrollments->groupBy(function ($enrollment) {
            return $enrollment->course->semester ?? 'Not Set';
        });

        $totalEnrolled = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();

        return view('student.courses', compact(
            'student',
            'enrollments',
            'coursesBySemester',
            'totalEnrolled',
            'completedCourses'
        ));
    }

    /**
     * Show GPA calculator with course breakdown
     */
    public function calculator()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()
            ->with('course')
            ->get();

        // Calculate GPA (assuming 4.0 scale)
        $gpa = $this->calculateGPA($enrollments);
        
        // Get grade distribution
        $gradeDistribution = [
            'A' => $enrollments->whereBetween('grade', [90, 100])->count(),
            'B' => $enrollments->whereBetween('grade', [80, 89])->count(),
            'C' => $enrollments->whereBetween('grade', [70, 79])->count(),
            'D' => $enrollments->whereBetween('grade', [60, 69])->count(),
            'F' => $enrollments->where('grade', '<', 60)->count(),
        ];

        // Calculate trend (average of last 3 courses if available)
        $trend = $enrollments->take(3)->avg('grade') ?? 0;

        return view('student.calculator', compact(
            'student',
            'enrollments',
            'gpa',
            'gradeDistribution',
            'trend'
        ));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'student_id' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'program' => 'nullable|string|max:255',
        ]);

        $student->update($validated);

        return redirect()->route('student.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Calculate GPA from enrollments
     */
    private function calculateGPA($enrollments)
    {
        if ($enrollments->isEmpty()) {
            return 0;
        }

        $gradePoints = [
            'A' => 4.0, 'A-' => 3.7,
            'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
            'D+' => 1.3, 'D' => 1.0, 'F' => 0.0
        ];

        $totalPoints = 0;
        $count = 0;

        foreach ($enrollments as $enrollment) {
            // Convert numeric grade to letter grade
            $numericGrade = $enrollment->grade;
            
            if ($numericGrade >= 90) {
                $gpa = 4.0;
            } elseif ($numericGrade >= 85) {
                $gpa = 3.7;
            } elseif ($numericGrade >= 80) {
                $gpa = 3.3;
            } elseif ($numericGrade >= 75) {
                $gpa = 3.0;
            } elseif ($numericGrade >= 70) {
                $gpa = 2.7;
            } elseif ($numericGrade >= 65) {
                $gpa = 2.3;
            } elseif ($numericGrade >= 60) {
                $gpa = 2.0;
            } else {
                $gpa = 0.0;
            }

            $totalPoints += $gpa;
            $count++;
        }

        return $count > 0 ? round($totalPoints / $count, 2) : 0;
    }
}
