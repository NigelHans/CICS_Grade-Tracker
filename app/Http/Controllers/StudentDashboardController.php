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
            ->get();

        // Calculate GPA
        $gpa = $this->calculateGPA($enrollments);
        
        // Calculate statistics
        $totalEnrolled = $enrollments->count();
        $gradesRecorded = $enrollments->whereNotNull('grade')->count();
        $classCompletion = $totalEnrolled > 0 ? round(($gradesRecorded / $totalEnrolled) * 100) : 0;
        $atRiskCourses = $enrollments->where('grade', '<', 60)->whereNotNull('grade')->count();
        
        // Get recent courses (limit to 9 for dashboard display)
        $recentEnrollments = $enrollments->take(9);
        
        // Get grade distribution
        $gradeDistribution = [
            'A' => $enrollments->whereBetween('grade', [90, 100])->count(),
            'B' => $enrollments->whereBetween('grade', [80, 89])->count(),
            'C' => $enrollments->whereBetween('grade', [70, 79])->count(),
            'D' => $enrollments->whereBetween('grade', [60, 69])->count(),
            'F' => $enrollments->where('grade', '<', 60)->count(),
        ];

        return view('student.dashboard', compact(
            'student',
            'enrollments',
            'recentEnrollments',
            'gpa',
            'totalEnrolled',
            'classCompletion',
            'atRiskCourses',
            'gradeDistribution'
        ));
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
    /**
     * Calculate GPA on 1-5 scale (1 = highest/best, 5 = lowest/worst)
     */
    private function calculateGPA($enrollments)
    {
        if ($enrollments->isEmpty()) {
            return 0;
        }

        $totalPoints = 0;
        $count = 0;

        foreach ($enrollments as $enrollment) {
            // Convert numeric grade to GPA on 1-5 scale
            // 1 = Excellent (90-100)
            // 2 = Good (80-89)
            // 3 = Average (70-79)
            // 4 = Below Average (60-69)
            // 5 = Failing (<60)
            $numericGrade = $enrollment->grade;
            
            if ($numericGrade >= 90) {
                $gpa = 1.0;
            } elseif ($numericGrade >= 80) {
                $gpa = 2.0;
            } elseif ($numericGrade >= 70) {
                $gpa = 3.0;
            } elseif ($numericGrade >= 60) {
                $gpa = 4.0;
            } else {
                $gpa = 5.0;
            }

            $totalPoints += $gpa;
            $count++;
        }

        return $count > 0 ? round($totalPoints / $count, 2) : 0;
    }
}
