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
                <a href="{{ route('lecturer.courses') }}" class="sidebar-link">
                    <i class="fas fa-book"></i> Courses
                </a>
                <a href="{{ route('lecturer.class-view') }}" class="sidebar-link active">
                    <i class="fas fa-chalkboard"></i> Class View
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <h1 style="margin: 30px 0 25px 0; color: #333; font-size: 32px; font-weight: bold;">Class View</h1>

            <!-- Classes List -->
            <div class="classes-container" style="display: grid; gap: 20px; margin-bottom: 40px;">
                @forelse($courses as $course)
                    <div class="class-item" style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden; border: 2px solid #e0e0e0; transition: all 0.3s ease;">
                        <!-- Course Header (Clickable) -->
                        <div class="class-header" onclick="this.parentElement.classList.toggle('expanded')" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 30px; align-items: center; padding: 20px; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; cursor: pointer; user-select: none; transition: all 0.3s ease;">
                            <div class="class-info" style="display: flex; flex-direction: column; gap: 8px;">
                                <div class="class-code" style="font-size: 16px; font-weight: bold;">{{ $course->course_code }}</div>
                                <div class="class-title" style="font-size: 13px; opacity: 0.9;">{{ $course->course_title }}</div>
                            </div>
                            <div class="class-codes" style="font-size: 14px; text-align: center;">{{ implode(', ', array_filter([$course->course_code, ...(json_decode($course->related_codes ?? '[]'))])) }}</div>
                            <div class="class-college" style="font-size: 14px; text-align: center;">{{ $course->college ?? 'CICS' }}</div>
                            <div class="class-arrow" style="display: flex; align-items: center; justify-content: center; font-size: 18px; transition: transform 0.3s ease;">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Class Details (Expandable) -->
                        <div class="class-details">
                            <div class="details-content" style="padding: 20px;">
                                @php
                                    $students = $course->enrollments->map(function($enrollment) {
                                        return [
                                            'id' => $enrollment->student->id,
                                            'name' => $enrollment->student->name,
                                            'sr_code' => $enrollment->student->student_id,
                                            'grade' => $enrollment->grade,
                                            'enrollment_id' => $enrollment->id,
                                        ];
                                    })->sortBy('sr_code');
                                    
                                    $syllabus = json_decode($course->syllabus, true) ?? [];
                                @endphp

                                @if($students->count() > 0)
                                    <table class="students-table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                        <thead style="background-color: #f5f5f5;">
                                            <tr>
                                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600;">#</th>
                                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600;">SR-Code</th>
                                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-weight: 600;">Name</th>
                                                @foreach($syllabus as $component)
                                                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; font-weight: 600;">{{ chr(81 + $loop->index) }}</th>
                                                @endforeach
                                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; font-weight: 600;">%</th>
                                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd; font-weight: 600;">Final Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $index => $student)
                                                <tr style="border-bottom: 1px solid #eee; transition: background-color 0.2s ease;">
                                                    <td style="padding: 12px; text-align: center;">{{ $index + 1 }}</td>
                                                    <td style="padding: 12px;">{{ $student['sr_code'] }}</td>
                                                    <td style="padding: 12px;">{{ $student['name'] }}</td>
                                                    @foreach($syllabus as $component)
                                                        <td style="padding: 12px; text-align: center; color: #666;">-</td>
                                                    @endforeach
                                                    <td style="padding: 12px; text-align: center; color: #666;">-</td>
                                                    <td style="padding: 12px; text-align: center;">
                                                        @if($student['grade'] !== null)
                                                            <span style="
                                                                padding: 4px 12px;
                                                                border-radius: 4px;
                                                                font-weight: 600;
                                                                background-color: 
                                                                    @if($student['grade'] >= 90) #d4edda
                                                                    @elseif($student['grade'] >= 80) #cfe2ff
                                                                    @elseif($student['grade'] >= 70) #fff3cd
                                                                    @elseif($student['grade'] >= 60) #f8d7da
                                                                    @else #f8d7da
                                                                    @endif
                                                                ;
                                                                color: 
                                                                    @if($student['grade'] >= 90) #155724
                                                                    @elseif($student['grade'] >= 80) #004085
                                                                    @elseif($student['grade'] >= 70) #856404
                                                                    @elseif($student['grade'] >= 60) #721c24
                                                                    @else #721c24
                                                                    @endif
                                                                ;
                                                            ">{{ number_format($student['grade'], 2) }}</span>
                                                        @else
                                                            <span style="color: #999;">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="no-students" style="padding: 40px 20px; text-align: center; color: #999;">
                                        <p>No students enrolled in this course yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: #999;">
                        <p style="font-size: 16px;">No courses found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .class-header:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
    }

    .class-item:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
    }

    .class-details {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
        display: none;
    }

    .class-item.expanded .class-details {
        max-height: 2000px;
        overflow: visible;
        display: block !important;
    }

    .class-item.expanded .class-arrow {
        transform: rotate(180deg);
    }

    .students-table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .main-content {
        background-color: #f8f9fa;
        padding: 30px 40px;
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

    @media (max-width: 768px) {
        .class-header {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    // Handle class header clicks to close others and expand selected
    document.addEventListener('click', function(event) {
        const header = event.target.closest('.class-header');
        if (header) {
            const classItem = header.closest('.class-item');
            
            // Close all other classes
            document.querySelectorAll('.class-item').forEach(item => {
                if (item !== classItem && item.classList.contains('expanded')) {
                    item.classList.remove('expanded');
                }
            });
            
            // Toggle current class - handled by inline onclick
        }
    });
</script>

@endsection
