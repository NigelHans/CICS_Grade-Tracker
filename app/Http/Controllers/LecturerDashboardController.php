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
        
        // Get course statistics with detailed calculations
        $courseStats = [];
        $allEnrollments = [];
        $allGrades = [];
        
        foreach ($courses as $course) {
            $enrollments = $course->enrollments()->with('student')->get();
            $allEnrollments = array_merge($allEnrollments, $enrollments->toArray());
            
            $grades = $enrollments->pluck('grade')->filter();
            $allGrades = array_merge($allGrades, $grades->toArray());
            
            $gradeCount = $grades->count();
            $passCount = $grades->filter(fn($g) => $g >= 60)->count();
            
            $courseStats[$course->id] = [
                'enrolled_count' => $enrollments->count(),
                'average_grade' => $gradeCount > 0 ? round($grades->avg(), 2) : 0,
                'pass_rate' => $gradeCount > 0 ? round(($passCount / $gradeCount) * 100, 1) : 0,
                'pass_count' => $passCount,
                'fail_count' => $gradeCount - $passCount,
            ];
        }

        // Get low performing students (grade < 60)
        $lowPerformingStudents = Enrollment::with('student', 'course')
            ->whereIn('course_id', $courses->pluck('id'))
            ->whereNotNull('grade')
            ->where('grade', '<', 60)
            ->orderBy('grade', 'asc')
            ->get();

        // Calculate total students (distinct student count)
        $totalStudents = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->distinct('student_id')
            ->count('student_id');

        // Calculate overall statistics
        $overallAverageGrade = count($allGrades) > 0 ? round(array_sum($allGrades) / count($allGrades), 2) : 0;
        $overallPassRate = count($allGrades) > 0 ? round((count(array_filter($allGrades, fn($g) => $g >= 60)) / count($allGrades)) * 100, 1) : 0;
        $gradeCompletionRate = $totalStudents > 0 ? round((count($allGrades) / $totalStudents) * 100, 1) : 0;
        $atRiskCount = $lowPerformingStudents->count();

        // Get pending tasks (courses without grades or with incomplete grading)
        $pendingTasks = [];
        foreach ($courses as $course) {
            $enrollmentsWithoutGrades = $course->enrollments()
                ->whereNull('grade')
                ->count();
            
            if ($enrollmentsWithoutGrades > 0) {
                $pendingTasks[] = [
                    'course_id' => $course->id,
                    'course_code' => $course->course_code,
                    'pending_count' => $enrollmentsWithoutGrades,
                ];
            }
        }

        return view('lecturer.dashboard', compact(
            'lecturer',
            'courses',
            'courseStats',
            'lowPerformingStudents',
            'totalStudents',
            'overallAverageGrade',
            'overallPassRate',
            'gradeCompletionRate',
            'atRiskCount',
            'pendingTasks'
        ));
    }

    /**
     * Show lecturer courses page with expandable details
     */
    public function courses()
    {
        $lecturer = Auth::user();

        // Get courses taught by this lecturer with all details
        $courses = Course::where('lecturer_id', $lecturer->id)
            ->with(['enrollments.student'])
            ->orderBy('course_code')
            ->get();

        return view('lecturer.courses', compact('lecturer', 'courses'));
    }

    /**
     * Show class view with all students
     */
    public function classView()
    {
        $lecturer = Auth::user();
        
        // Get all courses for this lecturer with their enrollments and students
        $courses = Course::where('lecturer_id', $lecturer->id)
            ->with(['enrollments.student'])
            ->orderBy('course_code')
            ->get();

        return view('lecturer.class-view', compact('lecturer', 'courses'));
    }

    /**
     * Show lecturer profile
     */
    public function profile()
    {
        $lecturer = Auth::user();
        return view('lecturer.profile', compact('lecturer'));
    }

    /**
     * Update lecturer profile
     */
    public function updateProfile(Request $request)
    {
        $lecturer = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $lecturer->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $lecturer->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
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

        // Handle CSV upload
        if ($request->hasFile('csv_file')) {
            $validated = $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt'
            ]);

            $file = $request->file('csv_file');
            $fileContent = file_get_contents($file->getRealPath());
            $lines = array_filter(array_map('trim', explode("\n", $fileContent)));

            foreach ($lines as $line) {
                $parts = array_map('trim', explode(',', $line));
                if (count($parts) >= 2) {
                    $srCode = $parts[0];
                    $grade = $parts[1];

                    if (is_numeric($grade) && $grade >= 0 && $grade <= 100) {
                        $student = User::where('student_id', $srCode)->first();
                        if ($student) {
                            $enrollment = Enrollment::where('student_id', $student->id)
                                ->where('course_id', $courseId)
                                ->first();
                            
                            if ($enrollment) {
                                $enrollment->update(['grade' => (float) $grade]);
                            }
                        }
                    }
                }
            }

            return back()->with('success', 'Grades uploaded from CSV successfully!');
        }

        // Handle form grades
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:100',
        ]);

        // Update grades for each enrollment
        foreach ($validated['grades'] as $enrollmentId => $grade) {
            if ($grade !== null && $grade !== '') {
                Enrollment::findOrFail($enrollmentId)
                    ->update(['grade' => (float) $grade]);
            }
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

        $gradeCount = $grades->count();
        $averageGrade = $gradeCount > 0 ? round($grades->avg(), 2) : 0;
        $passRate = $gradeCount > 0 ? round(($grades->filter(fn($g) => $g >= 60)->count() / $gradeCount) * 100, 1) : 0;
        $passCount = $grades->filter(fn($g) => $g >= 60)->count();
        $failCount = $grades->filter(fn($g) => $g < 60)->count();
        $failRate = $gradeCount > 0 ? round(($failCount / $gradeCount) * 100, 1) : 0;

        $gradeDistribution = [
            'A' => $grades->filter(fn($g) => $g >= 90)->count(),
            'B' => $grades->filter(fn($g) => $g >= 80 && $g < 90)->count(),
            'C' => $grades->filter(fn($g) => $g >= 70 && $g < 80)->count(),
            'D' => $grades->filter(fn($g) => $g >= 60 && $g < 70)->count(),
            'F' => $grades->filter(fn($g) => $g < 60)->count(),
        ];

        // Calculate variance (expected vs actual)
        $exceedingCount = $enrollments->filter(fn($e) => $e->grade && $e->expected_grade && $e->grade > $e->expected_grade)->count();
        $meetingCount = $enrollments->filter(fn($e) => $e->grade && $e->expected_grade && $e->grade == $e->expected_grade)->count();
        $belowCount = $enrollments->filter(fn($e) => $e->grade && $e->expected_grade && $e->grade < $e->expected_grade)->count();
        
        $exceedingEnrollments = $enrollments->filter(fn($e) => $e->grade && $e->expected_grade && $e->grade > $e->expected_grade);
        $avgExceeding = $exceedingEnrollments->count() > 0 ? round($exceedingEnrollments->avg('grade') - $exceedingEnrollments->avg('expected_grade'), 2) : 0;
        
        $belowEnrollments = $enrollments->filter(fn($e) => $e->grade && $e->expected_grade && $e->grade < $e->expected_grade);
        $avgBelow = $belowEnrollments->count() > 0 ? round($belowEnrollments->avg('grade') - $belowEnrollments->avg('expected_grade'), 2) : 0;

        $lowPerformers = $enrollments->filter(fn($e) => $e->grade && $e->grade < 60);

        return view('lecturer.analytics', compact('lecturer', 'course', 'averageGrade', 'passRate', 'passCount', 'failCount', 'failRate', 'gradeCount', 'gradeDistribution', 'exceedingCount', 'meetingCount', 'belowCount', 'avgExceeding', 'avgBelow', 'lowPerformers'));
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
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect('/lecturer/dashboard')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:100'
        ]);

        $enrollment->update(['grade' => $validated['grade']]);

        // Check if this is an AJAX/JSON request
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Grade updated successfully!']);
        }

        return redirect()->route('lecturer.class-view', $enrollment->course_id)
            ->with('success', 'Grade updated successfully!');
    }
}