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
                <!-- Left Column: Overall GPA -->
                <div class="col-md-4">
                    <div class="gpa-card">
                        <h2>Your Overall GPA</h2>
                        <div class="gpa-value">{{ number_format($gpa, 2) }}</div>
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

                <!-- Right Column: Component Calculator -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                            <h4 style="margin: 0; color: white;">Component Grade Calculator</h4>
                            <p style="margin: 8px 0 0 0; font-size: 12px; color: #e3f2fd;">Select a course and input component scores to calculate your final grade</p>
                        </div>
                        <div class="card-body">
                            @if($enrollments->count() > 0)
                                <!-- Course Selector -->
                                <div style="margin-bottom: 25px;">
                                    <label for="courseSelect" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333; font-size: 15px;">Select Course:</label>
                                    <select id="courseSelect" class="form-control" onchange="loadComponentInputs()" style="padding: 12px; font-size: 14px; border: 2px solid #ddd; border-radius: 5px;">
                                        @foreach($enrollments as $enrollment)
                                            <option value="{{ $enrollment->id }}" data-syllabus="{{ htmlspecialchars(json_encode(json_decode($enrollment->course->syllabus, true) ?? [])) }}">
                                                {{ $enrollment->course->course_code }} - {{ $enrollment->course->course_title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input Form Section -->
                                <div id="componentsSection" style="display: block; margin-top: 25px;">
                                    <h5 style="color: #333; margin-bottom: 25px; font-weight: bold; font-size: 16px;">Enter Your Scores</h5>
                                    
                                    <div id="componentsList" style="display: grid; gap: 18px; margin-bottom: 40px;">
                                        <!-- Input fields will be generated here by JavaScript -->
                                    </div>

                                    <!-- Results Display -->
                                    <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 30px; border-radius: 8px; border: 1px solid #ddd;">
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                                            <!-- Final Grade -->
                                            <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; border: 2px solid #007bff;">
                                                <div style="color: #666; font-size: 11px; margin-bottom: 12px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Final Grade</div>
                                                <div style="font-size: 56px; font-weight: bold; color: #007bff; line-height: 1;">
                                                    <span id="finalGrade">--</span>
                                                </div>
                                            </div>

                                            <!-- Letter Grade -->
                                            <div style="background: white; padding: 20px; border-radius: 8px; text-align: center;">
                                                <div style="color: #666; font-size: 11px; margin-bottom: 12px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Letter Grade</div>
                                                <div id="letterGrade" style="font-size: 56px; font-weight: bold; color: white; background-color: #999; border-radius: 8px; padding: 10px; line-height: 1.2;">
                                                    --
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p style="text-align: center; color: #666; padding: 40px;">No courses enrolled yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Current Grades Table -->
                    <div class="card" style="margin-top: 25px;">
                        <div class="card-header">
                            <h4 style="margin: 0;">Current Grades</h4>
                        </div>
                        <div class="card-body">
                            @if($enrollments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Grade</th>
                                                <th>Letter</th>
                                                <th>GPA Points</th>
                                                <th>Credits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($enrollments as $enrollment)
                                                @php
                                                    $numGrade = $enrollment->grade ?? 0;
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
                                                    <td>
                                                        <span style="background-color: {{ $numGrade >= 75 ? '#28a745' : ($numGrade >= 60 ? '#ffc107' : '#dc3545') }}; padding: 5px 10px; border-radius: 3px; color: white; font-weight: bold;">
                                                            {{ $numGrade }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span style="background-color: {{ $numGrade >= 75 ? '#28a745' : ($numGrade >= 60 ? '#ffc107' : '#dc3545') }}; padding: 5px 10px; border-radius: 3px; color: white;">
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
                            @else
                                <p style="text-align: center; color: #666;">No grades available</p>
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
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    .component-group {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .component-input {
        display: flex;
        gap: 15px;
        align-items: flex-end;
    }

    .component-input input {
        width: 100px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 13px;
    }

    .component-input input:focus {
        outline: none;
        border-color: #007bff;
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

<script>
    function loadComponentInputs() {
        const courseSelect = document.getElementById('courseSelect');
        if (!courseSelect) return;
        
        const selected = courseSelect.options[courseSelect.selectedIndex];
        if (!selected || !selected.value) return;

        try {
            const syllabusStr = selected.getAttribute('data-syllabus');
            const syllabus = JSON.parse(decodeURIComponent(syllabusStr.replace(/&quot;/g, '"')));
            const componentsList = document.getElementById('componentsList');
            
            if (!componentsList) return;
            
            componentsList.innerHTML = '';

            if (!syllabus || syllabus.length === 0) {
                componentsList.innerHTML = '<p style="color: #999; padding: 20px; text-align: center;">No components defined</p>';
                return;
            }

            syllabus.forEach(component => {
                const maxPoints = component.max_points || 100;
                const html = `
                    <div style="display: grid; grid-template-columns: 1fr 140px; gap: 20px; align-items: center; padding: 18px; background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 6px;">
                        <div>
                            <div style="font-weight: 600; color: #222; font-size: 15px; margin-bottom: 4px;">${component.name}</div>
                            <div style="color: #666; font-size: 13px;">${component.percentage}% of grade</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="number" class="component-score" min="0" max="${maxPoints}" step="0.01" placeholder="0" data-percentage="${component.percentage}" data-max-points="${maxPoints}" onchange="calculateFinalGrade()" oninput="calculateFinalGrade()" style="width: 100%; padding: 10px; border: 2px solid #007bff; border-radius: 5px; font-size: 16px; font-weight: bold; text-align: center; box-sizing: border-box;">
                            <span style="color: #999; font-weight: bold; min-width: 50px;">/${maxPoints}</span>
                        </div>
                    </div>
                `;
                componentsList.insertAdjacentHTML('beforeend', html);
            });

        } catch (error) {
            console.error('Error loading components:', error);
        }
    }

    // Load on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadComponentInputs);
    } else {
        loadComponentInputs();
    }

    // Load when course changes
    document.addEventListener('change', function(e) {
        if (e.target.id === 'courseSelect') {
            loadComponentInputs();
        }
    });

    function calculateFinalGrade() {
        const inputs = document.querySelectorAll('.component-score');
        let total = 0;
        let count = 0;

        inputs.forEach(input => {
            const val = parseFloat(input.value);
            const pct = parseFloat(input.getAttribute('data-percentage'));
            const maxPoints = parseFloat(input.getAttribute('data-max-points')) || 100;
            
            if (!isNaN(val) && input.value !== '') {
                // Convert score to percentage, then apply weight
                const percentage = (val / maxPoints) * 100;
                total += (percentage * pct) / 100;
                count++;
            }
        });

        const finalEl = document.getElementById('finalGrade');
        const letterEl = document.getElementById('letterGrade');
        
        if (count === 0) {
            finalEl.textContent = '--';
            letterEl.textContent = '--';
            letterEl.style.backgroundColor = '#999';
            return;
        }

        const grade = total.toFixed(2);
        finalEl.textContent = grade;

        let letter, color;
        if (grade >= 90) { letter = 'A'; color = '#28a745'; }
        else if (grade >= 85) { letter = 'A-'; color = '#28a745'; }
        else if (grade >= 80) { letter = 'B+'; color = '#17a2b8'; }
        else if (grade >= 75) { letter = 'B'; color = '#17a2b8'; }
        else if (grade >= 70) { letter = 'B-'; color = '#ffc107'; }
        else if (grade >= 65) { letter = 'C+'; color = '#ffc107'; }
        else if (grade >= 60) { letter = 'C'; color = '#fd7e14'; }
        else { letter = 'F'; color = '#dc3545'; }

        letterEl.textContent = letter;
        letterEl.style.backgroundColor = color;
    }
</script>
@endsection
