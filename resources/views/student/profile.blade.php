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
                <a href="{{ route('student.profile') }}" class="sidebar-link active">
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
                <h1>Student Profile</h1>
                <div class="user-logout">
                    <span>{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="profile-container">
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div style="width: 150px; height: 150px; background-color: rgba(255,255,255,0.3); border-radius: 10px;"></div>
                        </div>
                        <div class="col-md-10">
                            <h2 style="margin: 0; font-size: 28px;">{{ $student->name }}</h2>
                            <p style="margin: 5px 0; font-size: 18px; opacity: 0.9;">Student ID: <strong>{{ $student->student_id ?? 'N/A' }}</strong></p>
                            <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">Email: {{ $student->email }}</p>
                            <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">Department: <strong>{{ $student->department ?? 'Computer Science' }}</strong></p>
                            <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">Year Level: <strong>{{ $student->year_level ?? '3rd Year' }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="card" style="margin-bottom: 30px;">
                    <div class="card-header">
                        <h4 style="margin: 0;">Personal Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Full Name</label>
                                    <p>{{ $student->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Student ID</label>
                                    <p>{{ $student->student_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Email Address</label>
                                    <p>{{ $student->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Contact Number</label>
                                    <p>{{ $student->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="card" style="margin-bottom: 30px;">
                    <div class="card-header">
                        <h4 style="margin: 0;">Academic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Department</label>
                                    <p>{{ $student->department ?? 'Computer Science' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Year Level</label>
                                    <p>{{ $student->year_level ?? '3rd Year' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Program</label>
                                    <p>{{ $student->program ?? 'BS Computer Science' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Enrollment Status</label>
                                    <p><span class="badge" style="background-color: #28a745; padding: 8px 12px; border-radius: 5px; color: white;">{{ $student->enrollment_status ?? 'Active' }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <div style="margin-bottom: 30px;">
                    <button class="btn btn-primary" style="padding: 10px 30px; font-size: 16px;">Edit Profile</button>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary" style="padding: 10px 30px; font-size: 16px;">Back to Dashboard</a>
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

    .profile-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        padding: 40px;
        border-radius: 10px;
        color: white;
        margin-bottom: 30px;
    }

    .info-group {
        margin-bottom: 20px;
    }

    .info-group label {
        display: block;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .info-group p {
        margin: 0;
        color: #333;
        font-size: 16px;
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

    .btn {
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
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
        margin-left: 10px;
    }

    .btn-secondary:hover {
        background-color: #545b62;
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