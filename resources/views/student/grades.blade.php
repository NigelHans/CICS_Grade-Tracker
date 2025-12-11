@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar-nav">
            <div class="sidebar-header">
                <h3 class="sidebar-title">CICS<br>GRADE TRACKER</h3>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ route('student.dashboard') }}" class="sidebar-link">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('student.profile') }}" class="sidebar-link">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{ route('student.grades') }}" class="sidebar-link active">
                    <i class="fas fa-star"></i> Grades
                </a>
                <a href="{{ route('student.courses') }}" class="sidebar-link">
                    <i class="fas fa-book"></i> Courses
                </a>
                <a href="{{ route('student.calculator') }}" class="sidebar-link">
                    <i class="fas fa-calculator"></i> Calculator
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <div class="page-header">
                <h1>Grades</h1>
                <div class="user-logout">
                    <span>{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row stats-container">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #007bff;">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalCourses }}</h3>
                            <p>Total Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #28a745;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($averageGrade, 2) }}</h3>
                            <p>Average Grade</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #17a2b8;">
                            <i class="fas fa-thumbs-up"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $passedCourses }}</h3>
                            <p>Passed Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #dc3545;">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $failedCourses }}</h3>
                            <p>Failed Courses</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades Table Organized by Semester/Year -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h4 style="margin: 0;">Course Grades by Semester</h4>
                </div>
                <div class="card-body">
                    @if($enrollments->count() > 0)
                        @php
                            $enrollmentsByYear = [];
                            foreach($enrollments as $enrollment) {
                                $year = date('Y', strtotime($enrollment->created_at ?? now()));
                                if (!isset($enrollmentsByYear[$year])) {
                                    $enrollmentsByYear[$year] = [];
                                }
                                $enrollmentsByYear[$year][] = $enrollment;
                            }
                            krsort($enrollmentsByYear);
                        @endphp

                        @foreach($enrollmentsByYear as $year => $yearEnrollments)
                            <div style="margin-bottom: 40px;">
                                <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #007bff;">
                                    <h5 style="margin: 0; color: #007bff; font-weight: bold;">Academic Year {{ $year }}</h5>
                                </div>

                                @php
                                    $groupedBySemester = [];
                                    foreach($yearEnrollments as $enrollment) {
                                        $semester = $enrollment->course->semester ?? 'Unknown';
                                        if (!isset($groupedBySemester[$semester])) {
                                            $groupedBySemester[$semester] = [];
                                        }
                                        $groupedBySemester[$semester][] = $enrollment;
                                    }
                                @endphp

                                @foreach($groupedBySemester as $semester => $enrollments)
                                    <div style="margin-bottom: 30px;">
                                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                            <h6 style="margin: 0; color: #333; background-color: #f0f0f0; padding: 10px 15px; border-radius: 5px; font-weight: bold;">
                                                <i class="fas fa-calendar"></i> {{ $semester }}
                                            </h6>
                                            @php
                                                $totalGrade = 0;
                                                $gradeCount = 0;
                                                foreach($enrollments as $e) {
                                                    if ($e->grade) {
                                                        $totalGrade += $e->grade;
                                                        $gradeCount++;
                                                    }
                                                }
                                                $semesterGPA = $gradeCount > 0 ? $totalGrade / $gradeCount : 0;
                                                $semesterGPALetter = '';
                                                if ($semesterGPA >= 90) $semesterGPALetter = 'A';
                                                elseif ($semesterGPA >= 80) $semesterGPALetter = 'B';
                                                elseif ($semesterGPA >= 70) $semesterGPALetter = 'C';
                                                elseif ($semesterGPA >= 60) $semesterGPALetter = 'D';
                                                else $semesterGPALetter = 'F';
                                            @endphp
                                            <div style="margin-left: auto; text-align: right;">
                                                <span style="color: #666; font-size: 13px;">GWA: </span>
                                                <span style="font-weight: bold; font-size: 16px; color: #007bff;">{{ number_format($semesterGPA, 2) }}</span>
                                                <span style="background-color: {{ $semesterGPA >= 75 ? '#28a745' : ($semesterGPA >= 60 ? '#ffc107' : '#dc3545') }}; color: white; padding: 3px 8px; border-radius: 3px; font-weight: bold; margin-left: 10px; font-size: 12px;">{{ $semesterGPALetter }}</span>
                                            </div>
                                        </div>

                                        <div class="table-responsive" style="margin-bottom: 20px;">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Course Code</th>
                                                        <th>Course Title</th>
                                                        <th>Credits</th>
                                                        <th>Grade</th>
                                                        <th>Letter</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($enrollments as $enrollment)
                                                        @php
                                                            $grade = $enrollment->grade ?? 0;
                                                            if ($grade >= 90) {
                                                                $letterGrade = 'A';
                                                            } elseif ($grade >= 85) {
                                                                $letterGrade = 'A-';
                                                            } elseif ($grade >= 80) {
                                                                $letterGrade = 'B+';
                                                            } elseif ($grade >= 75) {
                                                                $letterGrade = 'B';
                                                            } elseif ($grade >= 70) {
                                                                $letterGrade = 'B-';
                                                            } elseif ($grade >= 65) {
                                                                $letterGrade = 'C+';
                                                            } elseif ($grade >= 60) {
                                                                $letterGrade = 'C';
                                                            } else {
                                                                $letterGrade = 'F';
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td><strong>{{ $enrollment->course->course_code ?? 'N/A' }}</strong></td>
                                                            <td>{{ $enrollment->course->course_title ?? 'N/A' }}</td>
                                                            <td>{{ $enrollment->course->credits ?? '3' }}</td>
                                                            <td>
                                                                <span class="grade-badge" style="background-color: {{ $grade >= 75 ? '#28a745' : ($grade >= 60 ? '#ffc107' : '#dc3545') }}; padding: 5px 10px; border-radius: 3px; color: white; font-weight: bold;">
                                                                    {{ $grade }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span style="background-color: {{ $grade >= 75 ? '#28a745' : ($grade >= 60 ? '#ffc107' : '#dc3545') }}; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold; font-size: 13px;">{{ $letterGrade }}</span>
                                                            </td>
                                                            <td>
                                                                @if($grade >= 60)
                                                                    <span class="badge" style="background-color: #28a745; padding: 5px 10px; border-radius: 3px; color: white;">Passed</span>
                                                                @else
                                                                    <span class="badge" style="background-color: #dc3545; padding: 5px 10px; border-radius: 3px; color: white;">Failed</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 40px;">
                            <p style="color: #666; font-size: 16px;">No grades available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sidebar-nav {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        min-height: 100vh;
        padding: 20px 0;
        position: sticky;
        top: 0;
    }

    .sidebar-header {
        text-align: center;
        color: white;
        margin-bottom: 30px;
        padding: 0 15px;
    }

    .sidebar-title {
        font-weight: bold;
        font-size: 18px;
        line-height: 1.2;
        margin: 0;
    }

    .sidebar-menu {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .sidebar-link {
        display: block;
        color: white;
        padding: 15px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-left-color: white;
    }

    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left-color: white;
        font-weight: bold;
    }

    .main-content {
        padding: 30px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: #333;
        font-weight: bold;
        margin: 0;
    }

    .user-logout {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #333;
    }

    .stats-container {
        margin-bottom: 30px;
        gap: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .stat-content h3 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .stat-content p {
        margin: 5px 0 0 0;
        color: #666;
        font-size: 14px;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        padding: 20px;
        background-color: #007bff;
        color: white;
        border-bottom: 1px solid #ddd;
    }

    .card-body {
        padding: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 12px;
        text-align: left;
        font-weight: bold;
        color: #333;
        border-bottom: 2px solid #ddd;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .grade-badge {
        padding: 8px 12px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
    }

    .badge {
        display: inline-block;
    }

    .btn {
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }
</style>
@endsection
