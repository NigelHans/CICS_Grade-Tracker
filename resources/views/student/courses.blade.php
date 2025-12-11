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
                <a href="{{ route('student.courses') }}" class="sidebar-link active">
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
                <h1>My Courses</h1>
                <div class="user-logout">
                    <span>{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row stats-container">
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #007bff;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalEnrolled }}</h3>
                            <p>Total Enrolled Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #28a745;">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $completedCourses }}</h3>
                            <p>Completed Courses</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses by Semester -->
            @foreach($coursesBySemester as $semester => $enrollmentGroup)
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                        <h4 style="margin: 0;">{{ $semester }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="courses-grid">
                            @foreach($enrollmentGroup as $enrollment)
                                <div class="course-card">
                                    <div class="course-header">
                                        <h5>{{ $enrollment->course->course_code ?? 'N/A' }}</h5>
                                        <span class="status-badge" style="background-color: {{ $enrollment->status == 'completed' ? '#28a745' : '#007bff' }};">
                                            {{ $enrollment->status ?? 'Ongoing' }}
                                        </span>
                                    </div>
                                    <h4>{{ $enrollment->course->course_title ?? 'N/A' }}</h4>
                                    <div class="course-details">
                                        <p><strong>Instructor:</strong> {{ $enrollment->course->instructor ?? 'N/A' }}</p>
                                        <p><strong>Credits:</strong> {{ $enrollment->course->credits ?? '3' }}</p>
                                        <p><strong>Room:</strong> {{ $enrollment->course->room ?? 'N/A' }}</p>
                                        @if($enrollment->grade)
                                            <p><strong>Current Grade:</strong> 
                                                <span class="grade-badge" style="background-color: {{ $enrollment->grade >= 75 ? '#28a745' : ($enrollment->grade >= 60 ? '#ffc107' : '#dc3545') }};">
                                                    {{ $enrollment->grade }}
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                    <button type="button" class="view-details-btn" onclick="showSyllabus('{{ $enrollment->id }}', '{{ $enrollment->course->course_code }}', '{{ addslashes($enrollment->course->syllabus) }}')">
                                        <i class="fas fa-file-alt"></i> View Syllabus →
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            @if($enrollments->count() == 0)
                <div class="card" style="margin-top: 30px;">
                    <div class="card-body" style="text-align: center; padding: 60px 20px;">
                        <p style="color: #666; font-size: 16px;">No courses enrolled yet.</p>
                    </div>
                </div>
            @endif
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

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .course-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .course-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .course-header h5 {
        margin: 0;
        font-size: 14px;
        color: #666;
        font-weight: bold;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .course-card h4 {
        margin: 10px 0;
        font-size: 18px;
        color: #333;
    }

    .course-details {
        margin: 15px 0;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .course-details p {
        margin: 8px 0;
        color: #666;
        font-size: 14px;
    }

    .course-details strong {
        color: #333;
    }

    .grade-badge {
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }

    .view-details-btn {
        display: inline-block;
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .view-details-btn:hover {
        color: #0056b3;
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

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px;
        background-color: #007bff;
        color: white;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        opacity: 0.8;
    }

    .modal-body {
        padding: 25px;
    }

    .syllabus-component {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid #007bff;
    }

    .syllabus-component h5 {
        margin: 0 0 8px 0;
        color: #333;
        font-weight: bold;
    }

    .syllabus-component p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .component-percentage {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: bold;
        margin-left: 10px;
    }
</style>

<!-- Syllabus Modal -->
<div id="syllabusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Course Syllabus</h3>
            <button class="modal-close" onclick="closeSyllabus()">×</button>
        </div>
        <div class="modal-body">
            <div id="syllabusContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    function showSyllabus(enrollmentId, courseCode, syllabusStr) {
        try {
            const syllabus = JSON.parse(syllabusStr);
            const modal = document.getElementById('syllabusModal');
            const modalTitle = document.getElementById('modalTitle');
            const content = document.getElementById('syllabusContent');

            modalTitle.textContent = `${courseCode} - Course Syllabus`;

            if (!syllabus || syllabus.length === 0) {
                content.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No assessment components defined for this course.</p>';
            } else {
                let html = '<div style="margin-bottom: 20px;"><h5 style="margin-top: 0; color: #333;">Assessment Components</h5>';
                
                syllabus.forEach(component => {
                    const maxPoints = component.max_points ? ` (${component.max_points} points)` : '';
                    html += `
                        <div class="syllabus-component">
                            <h5>
                                ${component.name}
                                <span class="component-percentage">${component.percentage}%${maxPoints}</span>
                            </h5>
                            <p>${component.description || 'No description provided'}</p>
                        </div>
                    `;
                });

                let totalPercentage = syllabus.reduce((sum, c) => sum + parseFloat(c.percentage || 0), 0);
                html += `</div><div style="padding: 15px; background-color: #f0f0f0; border-radius: 5px; text-align: right;">
                    <strong>Total Assessment Weight: ${totalPercentage}%</strong>
                </div>`;

                content.innerHTML = html;
            }

            modal.classList.add('show');
        } catch (error) {
            console.error('Error parsing syllabus:', error);
            const modal = document.getElementById('syllabusModal');
            const content = document.getElementById('syllabusContent');
            content.innerHTML = '<p style="color: #dc3545;">Error loading syllabus data. Please try again.</p>';
            modal.classList.add('show');
        }
    }

    function closeSyllabus() {
        document.getElementById('syllabusModal').classList.remove('show');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('syllabusModal');
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    }
</script>
@endsection
