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
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('lecturer.class-view', $course->id) }}" class="sidebar-link active">
                    <i class="fas fa-users"></i> Class View
                </a>
                <a href="{{ route('lecturer.syllabus', $course->id) }}" class="sidebar-link">
                    <i class="fas fa-scroll"></i> Syllabus
                </a>
                <a href="{{ route('lecturer.grade-upload', $course->id) }}" class="sidebar-link">
                    <i class="fas fa-upload"></i> Grade Upload
                </a>
                <a href="{{ route('lecturer.analytics', $course->id) }}" class="sidebar-link">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <div class="page-header">
                <div>
                    <a href="{{ route('lecturer.dashboard') }}" class="btn-back">← Back</a>
                    <h1>{{ $course->course_code }} - {{ $course->course_title }}</h1>
                </div>
                <div class="course-info">
                    <p><strong>Semester:</strong> {{ $course->semester }}</p>
                    <p><strong>Room:</strong> {{ $course->room }}</p>
                </div>
            </div>

            <!-- Class Summary -->
            <div class="row stats-container">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #007bff;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $students->count() }}</h3>
                            <p>Total Enrolled</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #28a745;">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($classAverage, 2) }}</h3>
                            <p>Class Average</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #ffc107;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $passCount }}</h3>
                            <p>Passed (≥60)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #dc3545;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $failCount }}</h3>
                            <p>Failed (<60)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="card">
                <div class="card-header">
                    <h4 style="margin: 0;">Student Grades & Progress</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SR Code</th>
                                    <th>Student Name</th>
                                    <th>Current Grade</th>
                                    <th>Expected Grade</th>
                                    <th>Difference</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $enrollment)
                                    <tr>
                                        <td><code>{{ $enrollment->student->sr_code ?? 'N/A' }}</code></td>
                                        <td>{{ $enrollment->student->name }}</td>
                                        <td>
                                            <span class="grade-badge" style="background-color: {{ $enrollment->grade >= 80 ? '#28a745' : ($enrollment->grade >= 70 ? '#ffc107' : ($enrollment->grade >= 60 ? '#fd7e14' : '#dc3545')) }};">
                                                {{ $enrollment->grade ?? '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="grade-badge" style="background-color: #6c757d;">
                                                {{ $enrollment->expected_grade ?? '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($enrollment->grade && $enrollment->expected_grade)
                                                @php
                                                    $diff = $enrollment->grade - $enrollment->expected_grade;
                                                    $diffColor = $diff >= 0 ? '#28a745' : '#dc3545';
                                                    $diffSymbol = $diff >= 0 ? '+' : '';
                                                @endphp
                                                <span style="color: {{ $diffColor }}; font-weight: bold;">
                                                    {{ $diffSymbol }}{{ $diff }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($enrollment->grade >= 60)
                                                <span class="badge" style="background-color: #28a745; padding: 5px 10px; border-radius: 3px; color: white;">
                                                    Passed
                                                </span>
                                            @else
                                                <span class="badge" style="background-color: #dc3545; padding: 5px 10px; border-radius: 3px; color: white;">
                                                    Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="openEditModal({{ $enrollment->id }}, {{ $enrollment->grade ?? 'null' }}, '{{ $enrollment->student->name }}')">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 20px; color: #666;">No students enrolled in this course yet</td>
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

<!-- Edit Grade Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeEditModal()">&times;</span>
        <h2 id="modalTitle">Edit Grade</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="grade">New Grade:</label>
                <input type="number" id="grade" name="grade" min="0" max="100" step="0.1" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Grade</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
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
        margin: 10px 0 0 0;
    }

    .btn-back {
        display: inline-block;
        padding: 8px 12px;
        background-color: #6c757d;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-back:hover {
        background-color: #545b62;
    }

    .course-info {
        text-align: right;
        color: #666;
    }

    .course-info p {
        margin: 5px 0;
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

    .grade-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
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
        padding: 8px 12px;
        font-size: 13px;
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

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 400px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-close:hover,
    .modal-close:focus {
        color: black;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
</style>

<script>
    function openEditModal(enrollmentId, currentGrade, studentName) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const gradeInput = document.getElementById('grade');
        const title = document.getElementById('modalTitle');

        title.textContent = `Edit Grade for ${studentName}`;
        gradeInput.value = currentGrade || '';
        form.action = `/lecturer/enrollments/${enrollmentId}`;

        modal.style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
