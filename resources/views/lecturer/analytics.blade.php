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
                <a href="{{ route('lecturer.grade-upload', $course->id) }}" class="sidebar-link">
                    <i class="fas fa-upload"></i> Grade Upload
                </a>
                <a href="{{ route('lecturer.analytics', $course->id) }}" class="sidebar-link active">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <div class="page-header">
                <div>
                    <a href="{{ route('lecturer.dashboard') }}" class="btn-back">← Back</a>
                    <h1>Analytics - {{ $course->course_code }}</h1>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="row stats-container">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #007bff;">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($averageGrade, 2) }}</h3>
                            <p>Class Average</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #28a745;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $passCount }}</h3>
                            <p>Passed ({{ round($passRate, 1) }}%)</p>
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
                            <p>Failed ({{ round($failRate, 1) }}%)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #ffc107;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $gradeCount }}</h3>
                            <p>Graded Students</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px;">
                <!-- Grade Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Grade Distribution</h4>
                        </div>
                        <div class="card-body">
                            <div class="grade-distribution">
                                <div class="grade-bar">
                                    <div class="grade-label">A (90-100)</div>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ ($gradeDistribution['A'] / max($gradeCount, 1)) * 100 }}%; background-color: #28a745;"></div>
                                    </div>
                                    <div class="grade-count">{{ $gradeDistribution['A'] }}</div>
                                </div>
                                <div class="grade-bar">
                                    <div class="grade-label">B (80-89)</div>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ ($gradeDistribution['B'] / max($gradeCount, 1)) * 100 }}%; background-color: #17a2b8;"></div>
                                    </div>
                                    <div class="grade-count">{{ $gradeDistribution['B'] }}</div>
                                </div>
                                <div class="grade-bar">
                                    <div class="grade-label">C (70-79)</div>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ ($gradeDistribution['C'] / max($gradeCount, 1)) * 100 }}%; background-color: #ffc107;"></div>
                                    </div>
                                    <div class="grade-count">{{ $gradeDistribution['C'] }}</div>
                                </div>
                                <div class="grade-bar">
                                    <div class="grade-label">D (60-69)</div>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ ($gradeDistribution['D'] / max($gradeCount, 1)) * 100 }}%; background-color: #fd7e14;"></div>
                                    </div>
                                    <div class="grade-count">{{ $gradeDistribution['D'] }}</div>
                                </div>
                                <div class="grade-bar">
                                    <div class="grade-label">F (<60)</div>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ ($gradeDistribution['F'] / max($gradeCount, 1)) * 100 }}%; background-color: #dc3545;"></div>
                                    </div>
                                    <div class="grade-count">{{ $gradeDistribution['F'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Summary -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="margin: 0;">Performance Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="summary-item">
                                <label>Class Average:</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ min(($averageGrade / 100) * 100, 100) }}%; background-color: #007bff;"></div>
                                </div>
                                <span class="summary-value">{{ number_format($averageGrade, 2) }}/100</span>
                            </div>

                            <div class="summary-item">
                                <label>Pass Rate:</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ $passRate }}%; background-color: #28a745;"></div>
                                </div>
                                <span class="summary-value">{{ round($passRate, 1) }}%</span>
                            </div>

                            <div class="summary-item">
                                <label>Excellent (90+):</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ ($gradeDistribution['A'] / max($gradeCount, 1)) * 100 }}%; background-color: #28a745;"></div>
                                </div>
                                <span class="summary-value">{{ round(($gradeDistribution['A'] / max($gradeCount, 1)) * 100, 1) }}%</span>
                            </div>

                            <div class="summary-item">
                                <label>Good (80-89):</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ ($gradeDistribution['B'] / max($gradeCount, 1)) * 100 }}%; background-color: #17a2b8;"></div>
                                </div>
                                <span class="summary-value">{{ round(($gradeDistribution['B'] / max($gradeCount, 1)) * 100, 1) }}%</span>
                            </div>

                            <div class="summary-item">
                                <label>Average (70-79):</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ ($gradeDistribution['C'] / max($gradeCount, 1)) * 100 }}%; background-color: #ffc107;"></div>
                                </div>
                                <span class="summary-value">{{ round(($gradeDistribution['C'] / max($gradeCount, 1)) * 100, 1) }}%</span>
                            </div>

                            <div class="summary-item">
                                <label>Below Average (60-69):</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ ($gradeDistribution['D'] / max($gradeCount, 1)) * 100 }}%; background-color: #fd7e14;"></div>
                                </div>
                                <span class="summary-value">{{ round(($gradeDistribution['D'] / max($gradeCount, 1)) * 100, 1) }}%</span>
                            </div>

                            <div class="summary-item">
                                <label>Failing (<60):</label>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ ($gradeDistribution['F'] / max($gradeCount, 1)) * 100 }}%; background-color: #dc3545;"></div>
                                </div>
                                <span class="summary-value">{{ round(($gradeDistribution['F'] / max($gradeCount, 1)) * 100, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade Variance Analysis -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h4 style="margin: 0;">Grade Variance (Current vs Expected)</h4>
                </div>
                <div class="card-body">
                    <div class="variance-summary">
                        <div class="variance-item">
                            <h5>Students Exceeding Expectations:</h5>
                            <p class="variance-count positive">{{ $exceedingCount }} students (+{{ $avgExceeding > 0 ? number_format($avgExceeding, 2) : '0' }} avg)</p>
                        </div>
                        <div class="variance-item">
                            <h5>Students Meeting Expectations:</h5>
                            <p class="variance-count neutral">{{ $meetingCount }} students</p>
                        </div>
                        <div class="variance-item">
                            <h5>Students Below Expectations:</h5>
                            <p class="variance-count negative">{{ $belowCount }} students ({{ $avgBelow > 0 ? number_format(-$avgBelow, 2) : '0' }} avg)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Performers -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-header">
                    <h4 style="margin: 0;">Students Needing Support (Grade < 60)</h4>
                </div>
                <div class="card-body">
                    @if($lowPerformers->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Current Grade</th>
                                        <th>Expected Grade</th>
                                        <th>Variance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowPerformers as $enrollment)
                                        <tr style="background-color: #fff5f5;">
                                            <td>{{ $enrollment->student->name }}</td>
                                            <td>
                                                <span class="grade-badge" style="background-color: #dc3545;">
                                                    {{ $enrollment->grade }}
                                                </span>
                                            </td>
                                            <td>{{ $enrollment->expected_grade ?? '—' }}</td>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="text-align: center; color: #666; padding: 20px;">No students with failing grades</p>
                    @endif
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

    .stats-container {
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

    .grade-distribution {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .grade-bar {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .grade-label {
        font-weight: bold;
        width: 80px;
        color: #333;
        font-size: 13px;
    }

    .progress-container {
        flex: 1;
        height: 25px;
        background-color: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
        min-width: 200px;
    }

    .progress-bar {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0 10px;
        color: white;
        font-size: 12px;
        font-weight: bold;
        transition: width 0.3s ease;
    }

    .grade-count {
        width: 40px;
        text-align: right;
        font-weight: bold;
        color: #333;
    }

    .summary-item {
        margin-bottom: 20px;
    }

    .summary-item label {
        display: block;
        font-weight: bold;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .summary-value {
        display: inline-block;
        margin-top: 5px;
        font-weight: bold;
        color: #333;
        font-size: 14px;
    }

    .variance-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .variance-item {
        padding: 20px;
        border-radius: 8px;
        background-color: #f8f9fa;
        text-align: center;
    }

    .variance-item h5 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 14px;
    }

    .variance-count {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .variance-count.positive {
        color: #28a745;
    }

    .variance-count.neutral {
        color: #ffc107;
    }

    .variance-count.negative {
        color: #dc3545;
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
        background-color: #f0f0f0;
    }

    .grade-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .variance-summary {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
