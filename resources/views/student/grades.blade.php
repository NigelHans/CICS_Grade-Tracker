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

            <!-- Grades Table -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h4 style="margin: 0;">Course Grades</h4>
                </div>
                <div class="card-body">
                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Credits</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td><strong>{{ $enrollment->course->course_code ?? 'N/A' }}</strong></td>
                                            <td>{{ $enrollment->course->course_title ?? 'N/A' }}</td>
                                            <td>{{ $enrollment->course->credits ?? '3' }}</td>
                                            <td>
                                                <span class="grade-badge" style="background-color: {{ $enrollment->grade >= 75 ? '#28a745' : ($enrollment->grade >= 60 ? '#ffc107' : '#dc3545') }};">
                                                    {{ $enrollment->grade ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($enrollment->grade >= 60)
                                                    <span class="badge" style="background-color: #28a745; padding: 5px 10px; border-radius: 3px; color: white;">Passed</span>
                                                @else
                                                    <span class="badge" style="background-color: #dc3545; padding: 5px 10px; border-radius: 3px; color: white;">Failed</span>
                                                @endif
                                            </td>
                                            <td>{{ $enrollment->course->semester ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
