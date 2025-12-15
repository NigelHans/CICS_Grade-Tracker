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
                <a href="{{ route('lecturer.dashboard') }}" class="sidebar-link">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('lecturer.profile') }}" class="sidebar-link">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{ route('lecturer.courses') }}" class="sidebar-link active">
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
                    <span>{{ Auth::user()->name }}</span>
                    <form action="{{ route('lecturer.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Courses Title -->
            <h1 style="margin: 30px 0 25px 0; color: #333; font-size: 32px; font-weight: bold;">Courses</h1>

            <!-- Courses List -->
            <div class="courses-container">
                @forelse($courses as $course)
                    <div class="course-item">
                        <!-- Course Header (Clickable) -->
                        <div class="course-header" onclick="this.parentElement.classList.toggle('expanded')">
                            <div class="course-info">
                                <div class="course-code">{{ $course->course_code }}</div>
                                <div class="course-codes-list">
                                    {{ implode(', ', array_filter([
                                        $course->course_code,
                                        ...(json_decode($course->related_codes ?? '[]'))
                                    ])) }}
                                </div>
                            </div>
                            <div class="course-college">{{ $course->college ?? 'CICS' }}</div>
                            <div class="course-type">{{ $course->course_type ?? 'MAJOR' }}</div>
                            <div class="course-arrow">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Course Details (Expandable) -->
                        <div class="course-details">
                            <div class="details-content">
                                @php
                                    $syllabus = json_decode($course->syllabus, true) ?? [];
                                    $totalPercentage = 0;
                                    $totalMaxPoints = 0;
                                    foreach($syllabus as $component) {
                                        $totalPercentage += $component['percentage'] ?? 0;
                                        $totalMaxPoints += $component['max_points'] ?? 0;
                                    }
                                @endphp

                                @if(count($syllabus) > 0)
                                    <table class="assessment-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Assessment Name</th>
                                                <th>Max Points</th>
                                                <th>Week</th>
                                                <th>Percentage Weight</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($syllabus as $index => $component)
                                                <tr>
                                                    <td><strong>{{ chr(81 + $index) }}</strong></td>
                                                    <td>{{ $component['name'] ?? 'N/A' }}</td>
                                                    <td>
                                                        @if(isset($component['max_points']))
                                                            <span class="max-points-badge">{{ $component['max_points'] }} pts</span>
                                                        @else
                                                            <span style="color: #999;">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($component['week']))
                                                            Week {{ $component['week'] }}
                                                        @else
                                                            <span style="color: #999;">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="percentage-badge">{{ $component['percentage'] ?? 0 }}%</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('lecturer.syllabus', $course->id) }}" class="edit-btn">Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="total-row">
                                                <td colspan="2"><strong>Total</strong></td>
                                                <td><strong>{{ $totalMaxPoints }} pts</strong></td>
                                                <td></td>
                                                <td><strong>{{ $totalPercentage }}%</strong></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <div class="no-assessments">
                                        <p>No assessments defined yet.</p>
                                        <a href="{{ route('lecturer.syllabus', $course->id) }}" class="btn btn-sm btn-primary">Create Assessments</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                        <p style="font-size: 16px; margin: 0;">No courses assigned yet</p>
                    </div>
                @endforelse
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

    .user-logout {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #333;
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

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .courses-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .course-item {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .course-item:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
    }

    .course-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 30px;
        align-items: center;
        padding: 20px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        cursor: pointer;
        user-select: none;
        transition: all 0.3s ease;
    }

    .course-header:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
    }

    .course-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .course-code {
        font-size: 16px;
        font-weight: bold;
    }

    .course-codes-list {
        font-size: 13px;
        opacity: 0.9;
    }

    .course-college {
        font-size: 14px;
        text-align: center;
    }

    .course-type {
        font-size: 14px;
        text-align: center;
    }

    .course-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .course-item.expanded .course-arrow {
        transform: rotate(180deg);
    }

    .course-details {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .course-item.expanded .course-details {
        max-height: 1500px;
        overflow: visible;
    }

    .details-content {
        padding: 30px;
        background-color: #f9f9f9;
        border-top: 1px solid #e0e0e0;
    }

    .assessment-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 5px;
        overflow: hidden;
    }

    .assessment-table thead {
        background-color: #f0f0f0;
    }

    .assessment-table th {
        padding: 15px;
        text-align: left;
        font-weight: bold;
        color: #333;
        border-bottom: 2px solid #ddd;
        font-size: 13px;
    }

    .assessment-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
    }

    .assessment-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .total-row {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .total-row td {
        border-bottom: 2px solid #ddd;
        padding: 15px;
    }

    .edit-btn {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .edit-btn:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .no-assessments {
        padding: 30px;
        text-align: center;
        color: #999;
    }

    .no-assessments p {
        margin: 0 0 20px 0;
        font-size: 16px;
    }

    .max-points-badge {
        background-color: #e7f3ff;
        color: #007bff;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 13px;
    }

    .percentage-badge {
        background-color: #e6f2ff;
        color: #0056b3;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 13px;
    }
</style>

<script>
    // Handle course header clicks to close others and expand selected
    document.addEventListener('click', function(event) {
        const header = event.target.closest('.course-header');
        if (header) {
            const courseItem = header.closest('.course-item');
            
            // Close all other courses
            document.querySelectorAll('.course-item').forEach(item => {
                if (item !== courseItem && item.classList.contains('expanded')) {
                    item.classList.remove('expanded');
                }
            });
            
            // Toggle current course - will be handled by inline onclick
            // This script just ensures only one is open at a time
        }
    });
</script>
@endsection
