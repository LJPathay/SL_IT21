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

Route::get('/student/dashboard', function () {
    return view('student.dashboard');
});

Route::get('/instructor/dashboard', function () {
    return view('instructor.dashboard');
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
