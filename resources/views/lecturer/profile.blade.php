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
                <a href="{{ route('lecturer.profile') }}" class="sidebar-link active">
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
                <h2 style="margin: 0; color: #333;">Lecturer Profile</h2>
                <div class="user-logout">
                    <span>{{ $lecturer->name }}</span>
                    <form action="{{ route('lecturer.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success" style="margin: 20px 0; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                    <strong>Success!</strong> {{ $message }}
                </div>
            @endif

            <!-- Profile Header Card -->
            <div class="profile-header-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 8px; padding: 40px; color: white; margin-bottom: 30px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 30px; align-items: center;">
                    <div style="width: 150px; height: 150px; background-color: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 70px; color: white;"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 32px; font-weight: bold;">{{ $lecturer->name }}</h2>
                        <p style="margin: 10px 0 5px 0; font-size: 16px; opacity: 0.9;">
                            <i class="fas fa-envelope"></i> {{ $lecturer->email }}
                        </p>
                        <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">
                            <i class="fas fa-building"></i> Department: <strong>{{ $lecturer->department ?? 'Not set' }}</strong>
                        </p>
                        @if($lecturer->specialization)
                            <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">
                                <i class="fas fa-star"></i> Specialization: <strong>{{ $lecturer->specialization }}</strong>
                            </p>
                        @endif
                        <p style="margin: 5px 0; font-size: 16px; opacity: 0.9;">
                            <i class="fas fa-phone"></i> {{ $lecturer->phone ?? 'Not provided' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Information Cards -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <!-- Contact Information -->
                <div class="profile-card">
                    <div class="card-header">
                        <h4 style="margin: 0;">
                            <i class="fas fa-phone"></i> Contact Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <label>Email Address</label>
                            <p style="margin: 5px 0; font-size: 15px;">{{ $lecturer->email }}</p>
                        </div>
                        <div class="info-group" style="margin-top: 15px;">
                            <label>Phone Number</label>
                            <p style="margin: 5px 0; font-size: 15px;">{{ $lecturer->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Department Information -->
                <div class="profile-card">
                    <div class="card-header">
                        <h4 style="margin: 0;">
                            <i class="fas fa-building"></i> Department Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <label>Department</label>
                            <p style="margin: 5px 0; font-size: 15px;">{{ $lecturer->department ?? 'Not set' }}</p>
                        </div>
                        <div class="info-group" style="margin-top: 15px;">
                            <label>Specialization</label>
                            <p style="margin: 5px 0; font-size: 15px;">{{ $lecturer->specialization ?? 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Section -->
            <div class="profile-card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0;">
                            <i class="fas fa-edit"></i> Edit Profile
                        </h4>
                        <button class="btn btn-primary btn-sm" onclick="toggleEditModal()" style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-pencil-alt"></i> Edit Information
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p style="color: #666; margin: 0;">Click the "Edit Information" button above to update your profile details.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Your Profile</h4>
                <button type="button" class="modal-close" onclick="toggleEditModal()">×</button>
            </div>
            <form action="{{ route('lecturer.update-profile') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Full Name -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="name" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $lecturer->name) }}" class="form-control" placeholder="Enter your full name" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;" required>
                        @error('name')
                            <span class="error-text" style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $lecturer->email) }}" class="form-control" placeholder="Enter your email address" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;" required>
                        @error('email')
                            <span class="error-text" style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="phone" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $lecturer->phone) }}" class="form-control" placeholder="Enter your phone number" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                        @error('phone')
                            <span class="error-text" style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="department" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $lecturer->department) }}" class="form-control" placeholder="Enter your department" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                        @error('department')
                            <span class="error-text" style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Specialization -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="specialization" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">Specialization</label>
                        <input type="text" id="specialization" name="specialization" value="{{ old('specialization', $lecturer->specialization) }}" class="form-control" placeholder="e.g., Database Systems, Web Development" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                        @error('specialization')
                            <span class="error-text" style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer" style="padding: 20px; border-top: 1px solid #e0e0e0; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="toggleEditModal()" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }

    .user-logout {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-logout span {
        font-size: 14px;
        color: #666;
    }

    .btn {
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        border: none;
        font-size: 13px;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 8px 16px;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .main-content {
        background-color: #f8f9fa;
        padding: 30px;
    }

    .sidebar-nav {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        min-height: 100vh;
        padding: 0;
        color: white;
    }

    .sidebar-header {
        padding: 25px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .sidebar-title {
        color: white;
        font-size: 16px;
        font-weight: bold;
        margin: 0;
        line-height: 1.3;
    }

    .sidebar-menu {
        padding-top: 20px;
    }

    .sidebar-link {
        display: block;
        padding: 15px 20px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background-color: rgba(0, 0, 0, 0.2);
        color: white;
        border-left-color: white;
    }

    .sidebar-link i {
        margin-right: 10px;
    }

    .profile-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .card-header {
        padding: 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
    }

    .card-header h4 {
        color: #333;
        margin: 0;
    }

    .card-body {
        padding: 20px;
    }

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        display: block;
        font-weight: 600;
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-group p {
        margin: 0;
        color: #333;
        font-size: 15px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 50px auto;
        max-width: 500px;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #aaa;
        cursor: pointer;
        padding: 0;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: #333;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 20px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
</style>

<script>
    function toggleEditModal() {
        const modal = document.getElementById('editModal');
        if (modal.style.display === 'none' || !modal.style.display) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
</script>
@endsection
