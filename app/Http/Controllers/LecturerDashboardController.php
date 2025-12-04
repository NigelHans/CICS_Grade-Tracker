<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;

class LecturerDashboardController extends Controller
{
    /**
     * Show lecturer dashboard with all courses
     */
    public function index()
    {
        $lecturer = Auth::user();

        // Get courses taught by this lecturer
        $courses = Course::where('lecturer_id', $lecturer->id)->get();
        
        // Get course statistics
        $courseStats = [];
        foreach ($courses as $course) {
            $enrollments = $course->enrollments()->get();
            $grades = $enrollments->pluck('grade')->filter();
            
            $courseStats[$course->id] = [
                'enrolled_count' => $enrollments->count(),
                'average_grade' => $grades->count() > 0 ? round($grades->avg(), 2) : 0,
                'pass_rate' => $grades->count() > 0 ? round(($grades->filter(fn($g) => $g >= 60)->count() / $grades->count()) * 100, 1) : 0,
            ];
        }

        // Get low performing students
        $lowPerformingStudents = Enrollment::with('student', 'course')
            ->whereIn('course_id', $courses->pluck('id'))
            ->whereNotNull('grade')
            ->where('grade', '<', 60)
            ->limit(10)
            ->get();

        $totalStudents = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->distinct('student_id')
            ->count('student_id');

        return view('lecturer.dashboard', compact('lecturer', 'courses', 'courseStats', 'lowPerformingStudents', 'totalStudents'));
    }

    /**
     * Show class view with all students
     */
    public function classView($courseId)
    {
        $lecturer = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $students = Enrollment::with('student')
            ->where('course_id', $courseId)
            ->get();

        $classAverage = $students->pluck('grade')->filter()->avg();

        return view('lecturer.class-view', compact('lecturer', 'course', 'students', 'classAverage'));
    }

    /**
     * Show syllabus editor
     */
    public function syllabus($courseId)
    {
        $lecturer = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $assessments = $course->syllabus ? json_decode($course->syllabus, true) : [];

        return view('lecturer.syllabus-editor', compact('lecturer', 'course', 'assessments'));
    }

    /**
     * Save syllabus
     */
    public function saveSyllabus($courseId, Request $request)
    {
        $lecturer = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'assessments' => 'required|array',
            'assessments.*.name' => 'required|string',
            'assessments.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        $total = array_sum(array_column($validated['assessments'], 'percentage'));
        if ($total != 100) {
            return back()->withErrors(['assessments' => 'Total percentage must equal 100%']);
        }

        $course->update(['syllabus' => json_encode($validated['assessments'])]);

        return redirect()->route('lecturer.class-view', $courseId)->with('success', 'Syllabus saved!');
    }

    /**
     * Show grade upload page
     */
    public function gradeUpload($courseId)
    {
        $lecturer = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $students = Enrollment::with('student')
            ->where('course_id', $courseId)
            ->get();

        return view('lecturer.grade-upload', compact('lecturer', 'course', 'students'));
    }

    /**
     * Store uploaded grades
     */
    public function storeGrades($courseId, Request $request)
    {
        $lecturer = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|numeric',
            'grades.*.grade' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated['grades'] as $gradeData) {
            Enrollment::findOrFail($gradeData['enrollment_id'])
                ->update(['grade' => $gradeData['grade']]);
        }

        return back()->with('success', 'Grades updated successfully!');
    }

    /**
     * Show analytics
     */
    public function analytics($courseId)
    {
        $lecturer = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $enrollments = $course->enrollments()->get();
        $grades = $enrollments->pluck('grade')->filter();

        $analytics = [
            'total_students' => $enrollments->count(),
            'graded_students' => $grades->count(),
            'average_grade' => $grades->count() > 0 ? round($grades->avg(), 2) : 0,
            'pass_rate' => $grades->count() > 0 ? round(($grades->filter(fn($g) => $g >= 60)->count() / $grades->count()) * 100, 1) : 0,
            'fail_rate' => $grades->count() > 0 ? round(($grades->filter(fn($g) => $g < 60)->count() / $grades->count()) * 100, 1) : 0,
            'grade_distribution' => [
                'A' => $grades->filter(fn($g) => $g >= 90)->count(),
                'B' => $grades->filter(fn($g) => $g >= 80 && $g < 90)->count(),
                'C' => $grades->filter(fn($g) => $g >= 70 && $g < 80)->count(),
                'D' => $grades->filter(fn($g) => $g >= 60 && $g < 70)->count(),
                'F' => $grades->filter(fn($g) => $g < 60)->count(),
            ]
        ];

        return view('lecturer.analytics', compact('lecturer', 'course', 'analytics', 'enrollments'));
    }

    // Legacy methods for backward compatibility
    public function courseDetails($courseId)
    {
        return $this->classView($courseId);
    }

    public function updateGrade($enrollmentId)
    {
        $lecturer = Auth::user();
        $enrollment = Enrollment::findOrFail($enrollmentId);
        
        if ($enrollment->course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        return view('lecturer.update-grade', compact('lecturer', 'enrollment'));
    }

    public function storeGrade($enrollmentId, Request $request)
    {
        $lecturer = Auth::user();
        $enrollment = Enrollment::findOrFail($enrollmentId);
        
        if ($enrollment->course->lecturer_id !== $lecturer->id) {
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:100'
        ]);

        $enrollment->update(['grade' => $validated['grade']]);

        return redirect()->route('lecturer.class-view', $enrollment->course_id)
            ->with('success', 'Grade updated successfully!');
    }
}