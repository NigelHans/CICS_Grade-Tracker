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
                <a href="{{ route('student.grades') }}" class="sidebar-link">
                    <i class="fas fa-star"></i> Grades
                </a>
                <a href="{{ route('student.courses') }}" class="sidebar-link">
                    <i class="fas fa-book"></i> Courses
                </a>
                <a href="{{ route('student.calculator') }}" class="sidebar-link active">
                    <i class="fas fa-calculator"></i> Calculator
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <div class="page-header">
                <h1>GPA Calculator</h1>
                <div class="user-logout">
                    <span>{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <!-- GPA Display -->
                <div class="col-md-4">
                    <div class="gpa-card">
                        <h2>Your GPA</h2>
                        <div class="gpa-value">{{ $gpa }}</div>
                        <p class="gpa-scale">on a 4.0 scale</p>
                        <div class="gpa-progress">
                            <div class="progress-bar" style="width: {{ ($gpa / 4.0) * 100 }}%; background-color: {{ $gpa >= 3.5 ? '#28a745' : ($gpa >= 3.0 ? '#ffc107' : '#dc3545') }};"></div>
                        </div>
                    </div>

                    <!-- Grade Distribution -->
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-header">
                            <h4 style="margin: 0;">Grade Distribution</h4>
                        </div>
                        <div class="card-body">
                            <div class="grade-dist-item">
                                <span class="grade-letter">A</span>
                                <span class="grade-count">{{ $gradeDistribution['A'] ?? 0 }}</span>
                            </div>
                            <div class="grade-dist-item">
                                <span class="grade-letter">B</span>
                                <span class="grade-count">{{ $gradeDistribution['B'] ?? 0 }}</span>
                            </div>
                            <div class="grade-dist-item">
                                <span class="grade-letter">C</span>
                                <span class="grade-count">{{ $gradeDistribution['C'] ?? 0 }}</span>
                            </div>
                            <div class="grade-dist-item">
                                <span class="grade-letter">D</span>
                                <span class="grade-count">{{ $gradeDistribution['D'] ?? 0 }}</span>
                            </div>
                            <div class="grade-dist-item">
                                <span class="grade-letter">F</span>
                                <span class="grade-count">{{ $gradeDistribution['F'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Calculation -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Grade Breakdown by Course</h4>
                        </div>
                        <div class="card-body">
                            @if($enrollments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Numeric Grade</th>
                                                <th>Letter Grade</th>
                                                <th>GPA Points</th>
                                                <th>Credits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($enrollments as $enrollment)
                                                @php
                                                    $numGrade = $enrollment->grade;
                                                    if ($numGrade >= 90) {
                                                        $letterGrade = 'A';
                                                        $gpaPoints = 4.0;
                                                    } elseif ($numGrade >= 85) {
                                                        $letterGrade = 'A-';
                                                        $gpaPoints = 3.7;
                                                    } elseif ($numGrade >= 80) {
                                                        $letterGrade = 'B+';
                                                        $gpaPoints = 3.3;
                                                    } elseif ($numGrade >= 75) {
                                                        $letterGrade = 'B';
                                                        $gpaPoints = 3.0;
                                                    } elseif ($numGrade >= 70) {
                                                        $letterGrade = 'B-';
                                                        $gpaPoints = 2.7;
                                                    } elseif ($numGrade >= 65) {
                                                        $letterGrade = 'C+';
                                                        $gpaPoints = 2.3;
                                                    } elseif ($numGrade >= 60) {
                                                        $letterGrade = 'C';
                                                        $gpaPoints = 2.0;
                                                    } else {
                                                        $letterGrade = 'F';
                                                        $gpaPoints = 0.0;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td><strong>{{ $enrollment->course->course_code ?? 'N/A' }}</strong></td>
                                                    <td>{{ $enrollment->course->course_title ?? 'N/A' }}</td>
                                                    <td>{{ $numGrade }}</td>
                                                    <td>
                                                        <span class="letter-grade-badge" style="background-color: {{ $numGrade >= 75 ? '#28a745' : ($numGrade >= 60 ? '#ffc107' : '#dc3545') }};">
                                                            {{ $letterGrade }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($gpaPoints, 2) }}</td>
                                                    <td>{{ $enrollment->course->credits ?? '3' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Statistics -->
                                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                                    <h5>Summary Statistics</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Total Courses:</strong> {{ $enrollments->count() }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Average Numeric Grade:</strong> {{ number_format($enrollments->avg('grade'), 2) }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Recent Trend:</strong> {{ number_format($trend, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="text-align: center; padding: 40px;">
                                    <p style="color: #666; font-size: 16px;">No grades available yet to calculate GPA.</p>
                                </div>
                            @endif
                        </div>
                    </div>
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

    .gpa-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
    }

    .gpa-card h2 {
        margin: 0 0 20px 0;
        color: #333;
        font-size: 20px;
    }

    .gpa-value {
        font-size: 48px;
        font-weight: bold;
        color: #007bff;
        margin: 20px 0;
    }

    .gpa-scale {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .gpa-progress {
        height: 10px;
        background-color: #eee;
        border-radius: 5px;
        margin-top: 20px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        transition: width 0.3s ease;
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

    .grade-dist-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .grade-dist-item:last-child {
        border-bottom: none;
    }

    .grade-letter {
        font-weight: bold;
        font-size: 16px;
        color: #333;
    }

    .grade-count {
        background-color: #f8f9fa;
        padding: 5px 10px;
        border-radius: 5px;
        color: #333;
        font-weight: bold;
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

    .letter-grade-badge {
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
</style>
@endsection
