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
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('lecturer.profile') }}" class="sidebar-link">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{ route('lecturer.courses') }}" class="sidebar-link">
                    <i class="fas fa-book"></i> Courses
                </a>
                <a href="{{ route('lecturer.class-view') }}" class="sidebar-link">
                    <i class="fas fa-chalkboard"></i> Class View
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h2 style="margin: 0; color: #333;">Lecturer</h2>
                <div class="user-logout">
                    <span>{{ $lecturer->name }}</span>
                    <form action="{{ route('lecturer.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Profile Header Card -->
            <div class="profile-header-card">
                <div style="display: flex; align-items: center; gap: 30px;">
                    <div style="width: 120px; height: 120px; background-color: rgba(255,255,255,0.3); border-radius: 10px;"></div>
                    <div style="color: white;">
                        <h2 style="margin: 0; font-size: 28px;">{{ $lecturer->name }}</h2>
                        <p style="margin: 5px 0; font-size: 16px;">Position</p>
                        <p style="margin: 5px 0; font-size: 16px;"><strong>College of Informatics and Computing Sciences</strong></p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <h1 style="margin: 30px 0 25px 0; color: #333; font-size: 32px; font-weight: bold;">Dashboard</h1>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card-large">
                    <h4 style="color: #666; margin: 0 0 10px 0;">Students</h4>
                    <h2 style="color: #007bff; margin: 0; font-size: 36px; font-weight: bold;">{{ $totalStudents }}</h2>
                    <p style="color: #999; margin: 8px 0 0 0; font-size: 13px;">First Semester A.Y. 2025</p>
                </div>

                <div class="stat-card-large">
                    <h4 style="color: #666; margin: 0 0 10px 0;">Courses Handled</h4>
                    <h2 style="color: #007bff; margin: 0; font-size: 36px; font-weight: bold;">{{ $courses->count() }}</h2>
                    <p style="color: #999; margin: 8px 0 0 0; font-size: 13px;">First Semester A.Y. 2025</p>
                </div>

                <div class="stat-card-large">
                    <h4 style="color: #666; margin: 0 0 10px 0;">Grade Completion</h4>
                    <h2 style="color: #007bff; margin: 0; font-size: 36px; font-weight: bold;">{{ number_format($gradeCompletionRate, 0) }}%</h2>
                    <p style="color: #999; margin: 8px 0 0 0; font-size: 13px;">Assessments Uploaded</p>
                </div>

                <div class="stat-card-large">
                    <h4 style="color: #666; margin: 0 0 10px 0;">At Risk</h4>
                    <h2 style="color: #007bff; margin: 0; font-size: 36px; font-weight: bold;">{{ $atRiskCount }}</h2>
                    <p style="color: #999; margin: 8px 0 0 0; font-size: 13px;">Students performing below passing</p>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Average Grade Chart -->
                <div class="card" style="grid-column: 1 / 2;">
                    <div class="card-header">
                        <h4 style="margin: 0;">Average grade per course</h4>
                    </div>
                    <div class="card-body" style="height: 300px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>

                <!-- Pending Tasks & Notifications -->
                <div style="grid-column: 2 / 3; display: flex; flex-direction: column; gap: 20px;">
                    <!-- Pending Tasks -->
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Pending Tasks</h4>
                        </div>
                        <div class="card-body">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @forelse($pendingTasks as $task)
                                    <li style="padding: 12px 0; border-bottom: 1px solid #eee; font-size: 14px;">
                                        <i class="fas fa-tasks" style="color: #007bff; margin-right: 10px;"></i>
                                        Upload grades for <strong>{{ $task['course_code'] }}</strong> ({{ $task['pending_count'] }} students)
                                    </li>
                                @empty
                                    <li style="padding: 12px 0; color: #999;">
                                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
                                        All grades uploaded!
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Student Notifications -->
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Student Notifications</h4>
                        </div>
                        <div class="card-body">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @if($lowPerformingStudents->count() > 0)
                                    @foreach($lowPerformingStudents->take(5) as $enrollment)
                                        <li style="padding: 12px 0; border-bottom: 1px solid #eee; font-size: 14px;">
                                            <i class="fas fa-exclamation-circle" style="color: #dc3545; margin-right: 10px;"></i>
                                            <strong>{{ $enrollment->student->name }}</strong> (Grade: {{ $enrollment->grade }}) in {{ $enrollment->course->course_code }}
                                        </li>
                                    @endforeach
                                    @if($lowPerformingStudents->count() > 5)
                                        <li style="padding: 12px 0; color: #007bff; font-weight: bold; text-align: center;">
                                            +{{ $lowPerformingStudents->count() - 5 }} more
                                        </li>
                                    @endif
                                @else
                                    <li style="padding: 12px 0; color: #28a745;">
                                        <i class="fas fa-smile" style="margin-right: 10px;"></i>
                                        All students are performing well!
                                    </li>
                                @endif
                            </ul>
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
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('gradeChart');
        
        if (!ctx) {
            console.error('gradeChart canvas not found');
            return;
        }

        const courseLabels = @json($courses->pluck('course_code')->toArray());
        const courseGrades = @json($courses->map(function($course) use ($courseStats) { 
            return isset($courseStats[$course->id]) ? round($courseStats[$course->id]['average_grade'], 2) : 0;
        })->toArray());

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: courseLabels,
                datasets: [{
                    label: 'Average Grade',
                    data: courseGrades,
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 10
                        }
                    }
                }
            }
        });
    });
</script>

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

    .page-header h2 {
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

    .profile-header-card {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        padding: 40px;
        border-radius: 10px;
        color: white;
        margin-bottom: 30px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card-large {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .stat-card-large:hover {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
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

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
