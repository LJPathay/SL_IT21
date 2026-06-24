<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/modules', function () {
    return view('admin.modules');
});

Route::get('/admin/quizzes', function () {
    return view('admin.quizzes');
});

Route::get('/admin/users', function () {
    return view('admin.users');
});

Route::get('/admin/phishing', function () {
    return view('admin.phishing');
});

Route::get('/admin/reports', function () {
    return view('admin.reports');
});

Route::get('/student/dashboard', function () {
    return view('student.dashboard');
});

Route::get('/student/courses', function () {
    return view('student.courses');
});

Route::get('/student/inbox', function () {
    return view('student.inbox');
});

Route::get('/student/leaderboard', function () {
    return view('student.leaderboard');
});

Route::get('/student/quizzes', function () {
    return view('student.quizzes');
});

Route::get('/student/certificates', function () {
    return view('student.certificates');
});

Route::get('/instructor/dashboard', function () {
    return view('instructor.dashboard');
});

Route::get('/instructor/students', function () {
    return view('instructor.students');
});

Route::get('/instructor/assessments', function () {
    return view('instructor.assessments');
});

// Mockup routes for Module Catalog & Enrollment
Route::get('/modules', function () {
    return view('modules.index');
});

Route::get('/modules/{id}', function ($id) {
    return view('modules.show', ['id' => $id]);
});

Route::get('/learn/{id}', function ($id) {
    return view('learn.show', ['id' => $id]);
});
