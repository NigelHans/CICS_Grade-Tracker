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
                <a href="{{ route('lecturer.syllabus', $course->id) }}" class="sidebar-link active">
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
                    <h1>Syllabus - {{ $course->course_code }}</h1>
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

            <div class="card">
                <div class="card-header">
                    <h4 style="margin: 0;">Assessment Components</h4>
                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #ddd;">Total percentage must equal 100%</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('lecturer.save-syllabus', $course->id) }}" id="syllabusForm">
                        @csrf
                        @method('POST')

                        <div id="assessments-container">
                            @php
                                $assessments = json_decode($course->syllabus, true) ?? [];
                            @endphp

                            @if(count($assessments) > 0)
                                @foreach($assessments as $index => $assessment)
                                    <div class="assessment-item" data-index="{{ $index }}">
                                        <div class="assessment-row">
                                            <div class="form-group">
                                                <label>Assessment Name</label>
                                                <input type="text" name="assessments[{{ $index }}][name]" class="form-control" value="{{ $assessment['name'] ?? '' }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Percentage (%)</label>
                                                <input type="number" name="assessments[{{ $index }}][percentage]" class="form-control percentage-input" value="{{ $assessment['percentage'] ?? 0 }}" min="0" max="100" step="0.1" required>
                                            </div>
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeAssessment(this)">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="assessment-item" data-index="0">
                                    <div class="assessment-row">
                                        <div class="form-group">
                                            <label>Assessment Name</label>
                                            <input type="text" name="assessments[0][name]" class="form-control" placeholder="e.g., Attendance" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Percentage (%)</label>
                                            <input type="number" name="assessments[0][percentage]" class="form-control percentage-input" value="0" min="0" max="100" step="0.1" required>
                                        </div>
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeAssessment(this)">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="total-percentage">
                            <strong>Total: <span id="total">0</span>%</strong>
                            <span id="total-status" class="status-indicator"></span>
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="addAssessment()">+ Add Assessment</button>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Save Syllabus</button>
                            <a href="{{ route('lecturer.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        </div>
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

    .alert-danger h4 {
        margin-top: 0;
    }

    .alert-danger ul {
        margin: 10px 0 0 20px;
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
        padding: 30px;
    }

    .assessment-item {
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #6f42c1;
    }

    .assessment-row {
        display: flex;
        gap: 20px;
        align-items: flex-end;
    }

    .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .form-group:nth-child(2) {
        flex: 0 0 150px;
    }

    .form-group:nth-child(3) {
        flex: 0 0 120px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
        font-size: 14px;
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

    .total-percentage {
        padding: 15px;
        background-color: #e7f3ff;
        border-left: 4px solid #007bff;
        border-radius: 5px;
        margin: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-percentage strong {
        font-size: 16px;
        color: #333;
    }

    #total {
        color: #007bff;
        font-weight: bold;
    }

    .status-indicator {
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
    }

    .status-indicator.valid {
        background-color: #28a745;
        color: white;
    }

    .status-indicator.invalid {
        background-color: #dc3545;
        color: white;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
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

    .btn-primary:hover:not(:disabled) {
        background-color: #0056b3;
    }

    .btn-primary:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
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
</style>

<script>
    let assessmentCount = {{ count($assessments) > 0 ? count($assessments) : 1 }};

    function addAssessment() {
        const container = document.getElementById('assessments-container');
        const newItem = document.createElement('div');
        newItem.className = 'assessment-item';
        newItem.dataset.index = assessmentCount;
        newItem.innerHTML = `
            <div class="assessment-row">
                <div class="form-group">
                    <label>Assessment Name</label>
                    <input type="text" name="assessments[${assessmentCount}][name]" class="form-control" placeholder="e.g., Midterm Exam" required>
                </div>
                <div class="form-group">
                    <label>Percentage (%)</label>
                    <input type="number" name="assessments[${assessmentCount}][percentage]" class="form-control percentage-input" value="0" min="0" max="100" step="0.1" required>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAssessment(this)">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        assessmentCount++;
        attachPercentageListeners();
        updateTotal();
    }

    function removeAssessment(btn) {
        btn.closest('.assessment-item').remove();
        updateTotal();
    }

    function updateTotal() {
        const percentageInputs = document.querySelectorAll('.percentage-input');
        let total = 0;

        percentageInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const totalElement = document.getElementById('total');
        const statusElement = document.getElementById('total-status');
        const submitBtn = document.getElementById('submitBtn');

        totalElement.textContent = total.toFixed(1);

        if (total === 100) {
            statusElement.textContent = '✓ Valid';
            statusElement.className = 'status-indicator valid';
            submitBtn.disabled = false;
        } else {
            statusElement.textContent = `${total < 100 ? '✗ ' : '✗ '}${100 - total > 0 ? (100 - total).toFixed(1) + '% remaining' : (total - 100).toFixed(1) + '% over'}`;
            statusElement.className = 'status-indicator invalid';
            submitBtn.disabled = true;
        }
    }

    function attachPercentageListeners() {
        const percentageInputs = document.querySelectorAll('.percentage-input');
        percentageInputs.forEach(input => {
            input.removeEventListener('input', updateTotal);
            input.addEventListener('input', updateTotal);
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        attachPercentageListeners();
        updateTotal();
    });
</script>
@endsection
