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
                <a href="{{ route('lecturer.dashboard') }}" class="sidebar-link active">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-file-pdf"></i> Reports
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <div class="page-header">
                <h1>Lecturer Dashboard</h1>
                <div class="user-logout">
                    <span>{{ $lecturer->name }}</span>
                    <form action="{{ route('lecturer.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="row stats-container">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #6f42c1;">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $courses->count() }}</h3>
                            <p>Courses Teaching</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #007bff;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalStudents }}</h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #28a745;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>
                                @php
                                    $passRates = [];
                                    foreach($courses as $c) {
                                        $passRates[] = $courseStats[$c->id]['pass_rate'] ?? 0;
                                    }
                                    $avgPassRate = count($passRates) > 0 ? array_sum($passRates) / count($passRates) : 0;
                                @endphp
                                {{ number_format($avgPassRate, 1) }}%
                            </h3>
                            <p>Avg Pass Rate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #dc3545;">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $lowPerformingStudents->count() }}</h3>
                            <p>At-Risk Students</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h4 style="margin: 0;">Your Courses</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Enrolled</th>
                                    <th>Avg Grade</th>
                                    <th>Pass Rate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <td><strong>{{ $course->course_code }}</strong></td>
                                        <td>{{ $course->course_title }}</td>
                                        <td>{{ $courseStats[$course->id]['enrolled_count'] ?? 0 }}</td>
                                        <td>{{ number_format($courseStats[$course->id]['average_grade'] ?? 0, 2) }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $courseStats[$course->id]['pass_rate'] >= 70 ? '#28a745' : '#ffc107' }}; padding: 5px 10px; border-radius: 3px; color: white;">
                                                {{ number_format($courseStats[$course->id]['pass_rate'] ?? 0, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('lecturer.class-view', $course->id) }}" class="btn btn-sm btn-primary">View Class</a>
                                            <a href="{{ route('lecturer.syllabus', $course->id) }}" class="btn btn-sm btn-secondary">Syllabus</a>
                                            <a href="{{ route('lecturer.analytics', $course->id) }}" class="btn btn-sm btn-info">Analytics</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px; color: #666;">No courses assigned yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Low Performing Students -->
            @if($lowPerformingStudents->count() > 0)
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                        <h4 style="margin: 0;">Students Needing Attention (Grade < 60)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Course</th>
                                        <th>Grade</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowPerformingStudents as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->student->name }}</td>
                                            <td>{{ $enrollment->course->course_code }}</td>
                                            <td>
                                                <span class="badge" style="background-color: #dc3545; padding: 5px 10px; border-radius: 3px; color: white;">
                                                    {{ $enrollment->grade }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('lecturer.update-grade', $enrollment->id) }}" class="btn btn-sm btn-warning">Update Grade</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .sidebar-nav {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
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
        gap: 20px;
        margin-bottom: 30px;
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
        background-color: #6f42c1;
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

    .btn {
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        padding: 8px 12px;
        font-size: 13px;
        margin-right: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .badge {
        display: inline-block;
    }
</style>
@endsection
