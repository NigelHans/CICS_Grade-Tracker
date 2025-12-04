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
                <a href="{{ route('lecturer.class-view', $course->id) }}" class="sidebar-link">
                    <i class="fas fa-users"></i> Class View
                </a>
                <a href="{{ route('lecturer.syllabus', $course->id) }}" class="sidebar-link">
                    <i class="fas fa-scroll"></i> Syllabus
                </a>
                <a href="{{ route('lecturer.grade-upload', $course->id) }}" class="sidebar-link active">
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
                    <h1>Grade Upload - {{ $course->course_code }}</h1>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <h4>Validation Errors:</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <h4>Success!</h4>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 style="margin: 0;">Batch Grade Upload</h4>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #ddd;">Enter grades for students (0-100 scale)</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('lecturer.store-grades', $course->id) }}" id="gradeForm">
                        @csrf

                        <div class="table-responsive">
                            <table class="table edit-table">
                                <thead>
                                    <tr>
                                        <th>SR Code</th>
                                        <th>Student Name</th>
                                        <th>Current Grade</th>
                                        <th>New Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $enrollment)
                                        <tr>
                                            <td><code>{{ $enrollment->student->sr_code ?? 'N/A' }}</code></td>
                                            <td>{{ $enrollment->student->name }}</td>
                                            <td>
                                                <span class="grade-display {{ $enrollment->grade >= 60 ? 'passed' : 'failed' }}">
                                                    {{ $enrollment->grade ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <input 
                                                    type="number" 
                                                    name="grades[{{ $enrollment->id }}]" 
                                                    class="form-control grade-input" 
                                                    value="{{ $enrollment->grade ?? '' }}" 
                                                    min="0" 
                                                    max="100" 
                                                    step="0.1"
                                                    placeholder="Enter grade">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" style="text-align: center; padding: 20px; color: #666;">No students enrolled in this course yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Upload Grades
                            </button>
                            <a href="{{ route('lecturer.class-view', $course->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Alternative: CSV Upload -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h4 style="margin: 0;">CSV Upload (Alternative)</h4>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #ddd;">Upload grades via CSV file (SR Code, Grade)</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('lecturer.store-grades', $course->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="csv-file">CSV File:</label>
                            <input type="file" id="csv-file" name="csv_file" accept=".csv" class="form-control">
                            <small style="color: #666;">Format: SR Code, Grade (one student per line)</small>
                        </div>
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-file-upload"></i> Upload CSV
                        </button>
                    </form>
                </div>
            </div>
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

    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert h4 {
        margin-top: 0;
        margin-bottom: 10px;
    }

    .alert ul {
        margin: 0 0 0 20px;
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
        margin-bottom: 20px;
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

    .edit-table code {
        background-color: #f0f0f0;
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 12px;
    }

    .grade-display {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-weight: bold;
    }

    .grade-display.passed {
        background-color: #28a745;
    }

    .grade-display.failed {
        background-color: #dc3545;
    }

    .grade-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .grade-input:focus {
        outline: none;
        border-color: #6f42c1;
        box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.25);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #6f42c1;
        box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.25);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
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

    small {
        display: block;
        margin-top: 5px;
    }
</style>
@endsection
