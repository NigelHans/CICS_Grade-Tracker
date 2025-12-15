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
                <a href="{{ route('student.dashboard') }}" class="sidebar-link active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('student.profile') }}" class="sidebar-link">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{ route('student.grades') }}" class="sidebar-link">
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
                <h1>Student Dashboard</h1>
                <div class="user-logout">
                    <span>{{ $student->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Student Info Card -->
            <div class="student-info-card">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <div style="width: 100px; height: 100px; background-color: rgba(0, 123, 255, 0.2); border-radius: 10px;"></div>
                    </div>
                    <div class="col-md-10">
                        <h2 style="margin: 0; font-size: 24px;">{{ $student->name }}</h2>
                        <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">Student ID: <strong>{{ $student->student_id ?? '23-08318' }}</strong></p>
                        <p style="margin: 5px 0; font-size: 14px; opacity: 0.9;">Email: {{ $student->email }}</p>
                        <p style="margin: 5px 0; font-size: 14px; opacity: 0.9;">Status: <span class="badge" style="background-color: #28a745; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">ENROLLED - First Semester</span></p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row stats-container" style="margin-top: 30px;">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #007bff;">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $gpa }}</h3>
                            <p>GPA</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #28a745;">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalEnrolled }}</h3>
                            <p>Enrolled Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #ffc107;">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $classCompletion }}%</h3>
                            <p>Class Completion</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #dc3545;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $atRiskCourses }}</h3>
                            <p>At Risk</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="row" style="margin-top: 30px;">
                <!-- GPA Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">GPA across SYs</h4>
                        </div>
                        <div class="card-body" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                            <div style="text-align: center; color: #999;">
                                <p style="font-size: 14px;">GPA Chart Placeholder</p>
                                <p style="font-size: 12px;">Install a charting library to display</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Assessments -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Upcoming Assessments</h4>
                        </div>
                        <div class="card-body">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                    <strong>CS 101 - Long Quiz</strong><br>
                                    <small style="color: #666;">Due: Dec 10, 2025</small>
                                </li>
                                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                    <strong>IT 311 - Lab Exam 2</strong><br>
                                    <small style="color: #666;">Due: Dec 12, 2025</small>
                                </li>
                                <li style="padding: 10px 0;">
                                    <strong>Final Exams</strong><br>
                                    <small style="color: #666;">Due: Dec 15 - Dec 22, 2025</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Courses -->
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Your Courses This Semester</h4>
                        </div>
                        <div class="card-body">
                            @if($recentEnrollments && $recentEnrollments->count() > 0)
                                <div class="courses-grid">
                                    @foreach($recentEnrollments as $enrollment)
                                        <div class="course-card-mini">
                                            <div class="course-code">{{ $enrollment->course->course_code ?? 'N/A' }}</div>
                                            <div class="course-title">{{ substr($enrollment->course->course_title ?? 'N/A', 0, 40) }}</div>
                                            @if($enrollment->grade !== null)
                                                <div class="course-grade" style="background-color: {{ $enrollment->grade >= 75 ? '#28a745' : ($enrollment->grade >= 60 ? '#ffc107' : '#dc3545') }};">
                                                    {{ number_format($enrollment->grade, 2) }}
                                                </div>
                                            @else
                                                <div class="course-grade" style="background-color: #007bff;">
                                                    No grade
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p style="text-align: center; color: #666;">No enrolled courses</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
    }

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

    .student-info-card {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        padding: 30px;
        border-radius: 10px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stats-container {
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
        background: white;
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

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }

    .course-card-mini {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .course-card-mini:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .course-code {
        font-size: 12px;
        color: #666;
        font-weight: bold;
    }

    .course-title {
        font-size: 13px;
        color: #333;
        margin: 8px 0;
        font-weight: 500;
    }

    .course-grade {
        padding: 6px 10px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        font-size: 14px;
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

    .badge {
        display: inline-block;
    }
</style>
@endsection
