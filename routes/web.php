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
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth')->name('auth.refresh');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/modules', function () {
            return view('admin.modules');
        })->name('admin.modules');
        Route::get('/admin/courses', function () {
            return view('admin.courses');
        })->name('admin.courses');
        Route::get('/admin/quizzes', function () {
            return view('admin.quizzes');
        })->name('admin.quizzes');
        Route::get('/admin/users', function () {
            return view('admin.users');
        })->name('admin.users');
        Route::get('/admin/reports', function () {
            return view('admin.reports');
        })->name('admin.reports');
        
        Route::get('/admin/audit-logs', [DashboardController::class, 'auditLogs'])->name('admin.audit-logs');
        Route::get('/admin/security-logs', [DashboardController::class, 'securityLogs'])->name('admin.security-logs');
    });

    // Instructor routes
    Route::middleware('role:instructor')->group(function () {
        Route::get('/instructor/dashboard', [DashboardController::class, 'index'])->name('instructor.dashboard');
        Route::get('/instructor/courses', function () {
            return view('instructor.courses');
        })->name('instructor.courses');
        Route::get('/instructor/students', function () {
            return view('instructor.students');
        })->name('instructor.students');
        Route::get('/instructor/assessments', function () {
            return view('instructor.assessments');
        })->name('instructor.assessments');
    });

    // Module routes - accessible by all authenticated users based on their role
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
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
        Route::get('/student/courses', function () {
            return view('student.courses');
        })->name('student.courses');
        Route::get('/student/quizzes', function () {
            return view('student.quizzes');
        })->name('student.quizzes');
        Route::get('/student/leaderboard', function () {
            return view('student.leaderboard');
        })->name('student.leaderboard');
        Route::get('/student/quizzes', function () {
            return view('student.quizzes');
        })->name('student.quizzes');
        Route::get('/student/certificates', function () {
            return view('student.certificates');
        })->name('student.certificates');
    });
});
