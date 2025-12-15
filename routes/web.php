<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LecturerAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\LecturerDashboardController;

// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
    
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
    
    Route::get('/lecturer/login', [LecturerAuthController::class, 'showLogin'])->name('lecturer.login');
    Route::post('/lecturer/login', [LecturerAuthController::class, 'authenticate'])->name('lecturer.login.submit');
    
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Protected routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/lecturer/logout', [LecturerAuthController::class, 'logout'])->name('lecturer.logout');
    
    // Student Dashboard routes
    Route::get('/student/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::post('/student/profile/update', [StudentDashboardController::class, 'updateProfile'])->name('student.update-profile');
    Route::get('/student/grades', [StudentDashboardController::class, 'grades'])->name('student.grades');
    Route::get('/student/courses', [StudentDashboardController::class, 'courses'])->name('student.courses');
    Route::get('/student/calculator', [StudentDashboardController::class, 'calculator'])->name('student.calculator');
    
    // Lecturer routes
    Route::get('/lecturer/dashboard', [LecturerDashboardController::class, 'index'])->name('lecturer.dashboard');
    Route::get('/lecturer/profile', [LecturerDashboardController::class, 'profile'])->name('lecturer.profile');
    Route::post('/lecturer/profile/update', [LecturerDashboardController::class, 'updateProfile'])->name('lecturer.update-profile');
    Route::get('/lecturer/courses', [LecturerDashboardController::class, 'courses'])->name('lecturer.courses');
    Route::get('/lecturer/course/{courseId}', [LecturerDashboardController::class, 'courseDetails'])->name('lecturer.course-details');
    Route::get('/lecturer/class', [LecturerDashboardController::class, 'classView'])->name('lecturer.class-view');
    Route::get('/lecturer/syllabus/{courseId}', [LecturerDashboardController::class, 'syllabus'])->name('lecturer.syllabus');
    Route::post('/lecturer/syllabus/{courseId}', [LecturerDashboardController::class, 'saveSyllabus'])->name('lecturer.save-syllabus');
    Route::get('/lecturer/grade-upload/{courseId}', [LecturerDashboardController::class, 'gradeUpload'])->name('lecturer.grade-upload');
    Route::post('/lecturer/grade-upload/{courseId}', [LecturerDashboardController::class, 'storeGrades'])->name('lecturer.store-grades');
    Route::get('/lecturer/analytics/{courseId}', [LecturerDashboardController::class, 'analytics'])->name('lecturer.analytics');
    Route::get('/lecturer/grade/{enrollmentId}/edit', [LecturerDashboardController::class, 'updateGrade'])->name('lecturer.update-grade');
    Route::put('/lecturer/enrollments/{enrollmentId}', [LecturerDashboardController::class, 'storeGrade'])->name('lecturer.update-enrollment');
    Route::post('/lecturer/grade/{enrollmentId}', [LecturerDashboardController::class, 'storeGrade'])->name('lecturer.store-grade');
    
    // Keep old routes for backward compatibility (redirect to student)
    Route::get('/dashboard', function () {
        return redirect('/student/dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
