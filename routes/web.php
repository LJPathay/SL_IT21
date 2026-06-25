<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/mfa', [AuthController::class, 'showMfaForm'])->name('login.mfa');
    Route::post('/login/mfa', [AuthController::class, 'verifyMfa'])->name('login.mfa.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Password reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth')->name('auth.refresh');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/modules', [DashboardController::class, 'adminModules'])->name('admin.modules');
        Route::get('/admin/courses', [DashboardController::class, 'adminCourses'])->name('admin.courses');
        Route::get('/admin/quizzes', [DashboardController::class, 'adminQuizzes'])->name('admin.quizzes');
        Route::get('/admin/users', [DashboardController::class, 'adminUsers'])->name('admin.users');
        Route::get('/admin/reports', [DashboardController::class, 'adminReports'])->name('admin.reports');
        
        Route::get('/admin/audit-logs', [DashboardController::class, 'auditLogs'])->name('admin.audit-logs');
        Route::get('/admin/security-logs', [DashboardController::class, 'securityLogs'])->name('admin.security-logs');
    });

    // Instructor routes
    Route::middleware('role:instructor')->group(function () {
        Route::get('/instructor/dashboard', [DashboardController::class, 'index'])->name('instructor.dashboard');
        Route::get('/instructor/courses', [DashboardController::class, 'instructorCourses'])->name('instructor.courses');
        Route::get('/instructor/students', [DashboardController::class, 'instructorStudents'])->name('instructor.students');
        Route::get('/instructor/assessments', [DashboardController::class, 'instructorAssessments'])->name('instructor.assessments');
    });

    // Module action routes - require authentication
    Route::post('/modules/{module}/enroll', [ModuleController::class, 'enroll'])->name('modules.enroll');
    Route::post('/modules/{module}/unenroll', [ModuleController::class, 'unenroll'])->name('modules.unenroll');
    Route::post('/modules/{module}/complete', [ModuleController::class, 'complete'])->name('modules.complete');
    Route::put('/modules/{module}/progress', [ModuleController::class, 'updateProgress'])->name('modules.updateProgress');

    Route::get('/learn/{id}', function ($id) {
        return view('learn.show', ['id' => $id]);
    })->name('learn.show');

    // Student routes
    Route::middleware('role:student')->group(function () {
        Route::get('/student/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/student/courses', [DashboardController::class, 'studentCourses'])->name('student.courses');
        Route::get('/student/quizzes', [DashboardController::class, 'studentQuizzes'])->name('student.quizzes');
        Route::get('/student/quizzes/{quiz}', [ModuleController::class, 'showQuiz'])->name('student.quizzes.show');
        Route::post('/student/quizzes/{quiz}/submit', [ModuleController::class, 'submitQuiz'])->name('student.quizzes.submit');
        Route::get('/student/certificates', [DashboardController::class, 'studentCertificates'])->name('student.certificates');
        Route::get('/student/inbox', [DashboardController::class, 'studentInbox'])->name('student.inbox');
    });
});

// Module view routes - accessible to guests (catalog browsing)
Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
