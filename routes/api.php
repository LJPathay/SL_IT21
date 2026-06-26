<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Note: Laravel Sanctum needs to be installed for API authentication
// Run: composer require laravel/sanctum
// Run: php artisan install:api

// Public routes (if any)
// Route::post('/login', [ApiController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/profile', [ApiController::class, 'profile']);

    // Dashboard stats
    Route::get('/dashboard/stats', [ApiController::class, 'dashboardStats']);

    // Courses
    Route::get('/courses', [ApiController::class, 'courses']);
    Route::get('/courses/{course}', [ApiController::class, 'course']);

    // Modules
    Route::get('/modules', [ApiController::class, 'modules']);
    Route::get('/modules/{module}', [ApiController::class, 'module']);

    // Enrollments
    Route::get('/enrollments', [ApiController::class, 'enrollments']);
    Route::post('/modules/{module}/enroll', [ApiController::class, 'enroll']);
    Route::put('/enrollments/{enrollment}/progress', [ApiController::class, 'updateProgress']);

    // Quiz Results
    Route::get('/quiz-results', [ApiController::class, 'quizResults']);

    // Certificates
    Route::get('/certificates', [ApiController::class, 'certificates']);
});
