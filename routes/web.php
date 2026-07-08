<?php

use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminModuleController;
use App\Http\Controllers\Admin\AdminQuizController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\PhishingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SecurityDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle.login')->name('login.post');
    Route::get('/login/mfa', [AuthController::class, 'showMfaForm'])->name('login.mfa');
    Route::post('/login/mfa', [AuthController::class, 'verifyMfa'])->middleware('throttle.login')->name('login.mfa.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle.login')->name('register.post');

    // Password reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle.login')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth')->name('auth.refresh');

Route::middleware('auth')->group(function () {
    Route::post('/security/mfa/toggle', [AuthController::class, 'toggleMfa'])->name('security.mfa.toggle');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/mfa/setup', [AuthController::class, 'setupMfa'])->name('profile.mfa.setup');
    Route::post('/profile/mfa/confirm', [AuthController::class, 'confirmMfa'])->name('profile.mfa.confirm');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get ('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get ('/admin/modules', [DashboardController::class, 'adminModules'])->name('admin.modules');
        Route::get ('/admin/courses', [DashboardController::class, 'adminCourses'])->name('admin.courses');
        Route::get ('/admin/quizzes', [DashboardController::class, 'adminQuizzes'])->name('admin.quizzes');
        Route::get ('/admin/users', [DashboardController::class, 'adminUsers'])->name('admin.users');
        Route::post('/admin/users', [DashboardController::class, 'storeUser'])->name('admin.users.store');
        Route::get ('/admin/reports', [DashboardController::class, 'adminReports'])->name('admin.reports');
        Route::get ('/admin/reports/export', [DashboardController::class, 'exportReports'])->name('admin.reports.export');

        Route::get ('/admin/audit-logs', [DashboardController::class, 'auditLogs'])->name('admin.audit-logs');
        Route::get ('/admin/security-logs', [DashboardController::class, 'securityLogs'])->name('admin.security-logs');

        // Security Dashboard routes
        Route::get ('/security/dashboard', [SecurityDashboardController::class, 'index'])->name('security.dashboard');
        Route::get ('/security/detections', [SecurityDashboardController::class, 'detections'])->name('security.detections');
        Route::get ('/security/detections/{detection}', [SecurityDashboardController::class, 'showDetection'])->name('security.detections.show');
        Route::post('/security/detections/{detection}/resolve', [SecurityDashboardController::class, 'resolveDetection'])->name('security.detections.resolve');
        Route::post('/security/detections/{detection}/force-reset', [SecurityDashboardController::class, 'forcePasswordReset'])->name('security.detections.force-reset');
        Route::post('/security/detections/{detection}/quarantine', [SecurityDashboardController::class, 'quarantineFile'])->name('security.detections.quarantine');
        Route::post('/security/detections/{detection}/delete-message', [SecurityDashboardController::class, 'deleteMessage'])->name('security.detections.delete-message');
        Route::post('/security/test/phishing', [SecurityDashboardController::class, 'testPhishing'])->middleware('throttle.api:10,1')->name('security.test.phishing');
        Route::post('/security/test/social-engineering', [SecurityDashboardController::class, 'testSocialEngineering'])->middleware('throttle.api:10,1')->name('security.test.social-engineering');
        Route::post('/security/test/password', [SecurityDashboardController::class, 'testPassword'])->middleware('throttle.api:10,1')->name('security.test.password');
        Route::post('/security/test/malware', [SecurityDashboardController::class, 'testMalware'])->middleware('throttle.api:10,1')->name('security.test.malware');
        Route::post('/security/test/online-activity', [SecurityDashboardController::class, 'testOnlineActivity'])->middleware('throttle.api:10,1')->name('security.test.online-activity');

        // Admin CRUD routes for courses
        Route::get ('/admin/courses/create', [DashboardController::class, 'adminCoursesCreate'])->name('admin.courses.create');
        Route::post('/admin/courses', [DashboardController::class, 'storeCourse'])->name('admin.courses.store');
        Route::get ('/admin/courses/{course}', [AdminCourseController::class, 'show'])->name('admin.courses.show');
        Route::get ('/admin/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('admin.courses.edit');
        Route::put ('/admin/courses/{course}', [AdminCourseController::class, 'update'])->name('admin.courses.update');
        Route::delete('/admin/courses/{course}', [AdminCourseController::class, 'destroy'])->name('admin.courses.destroy');
        Route::post('/admin/courses/bulk-delete', [AdminCourseController::class, 'bulkDelete'])->name('admin.courses.bulk-delete');
        Route::post('/admin/courses/{course}/toggle-status', [AdminCourseController::class, 'toggleStatus'])->name('admin.courses.toggle-status');

        // Admin CRUD routes for modules
        Route::get ('/admin/modules/create', [DashboardController::class, 'adminModulesCreate'])->name('admin.modules.create');
        Route::post('/admin/modules', [DashboardController::class, 'storeModule'])->name('admin.modules.store');
        Route::get ('/admin/modules/{module}', [AdminModuleController::class, 'show'])->name('admin.modules.show');
        Route::get ('/admin/modules/{module}/edit', [AdminModuleController::class, 'edit'])->name('admin.modules.edit');
        Route::put ('/admin/modules/{module}', [AdminModuleController::class, 'update'])->name('admin.modules.update');
        Route::delete('/admin/modules/{module}', [AdminModuleController::class, 'destroy'])->name('admin.modules.destroy');
        Route::post('/admin/modules/bulk-delete', [AdminModuleController::class, 'bulkDelete'])->name('admin.modules.bulk-delete');
        Route::post('/admin/modules/{module}/toggle-status', [AdminModuleController::class, 'toggleStatus'])->name('admin.modules.toggle-status');

        // Admin CRUD routes for lessons
        Route::get ('/admin/modules/{module}/lessons/create', [LessonController::class, 'create'])->name('admin.lessons.create');
        Route::post('/admin/modules/{module}/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
        Route::get ('/admin/modules/{module}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('admin.lessons.edit');
        Route::put ('/admin/modules/{module}/lessons/{lesson}', [LessonController::class, 'update'])->name('admin.lessons.update');
        Route::delete('/admin/modules/{module}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');

        // Admin CRUD routes for quizzes
        Route::get ('/admin/quizzes/create', [DashboardController::class, 'adminQuizzesCreate'])->name('admin.quizzes.create');
        Route::post('/admin/quizzes', [DashboardController::class, 'storeQuiz'])->name('admin.quizzes.store');
        Route::get ('/admin/quizzes/{quiz}', [AdminQuizController::class, 'show'])->name('admin.quizzes.show');
        Route::get ('/admin/quizzes/{quiz}/edit', [AdminQuizController::class, 'edit'])->name('admin.quizzes.edit');
        Route::put ('/admin/quizzes/{quiz}', [AdminQuizController::class, 'update'])->name('admin.quizzes.update');
        Route::delete('/admin/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])->name('admin.quizzes.destroy');
        Route::post('/admin/quizzes/bulk-delete', [AdminQuizController::class, 'bulkDelete'])->name('admin.quizzes.bulk-delete');
        Route::get ('/admin/quizzes/{quiz}/questions', [AdminQuizController::class, 'questions'])->name('admin.quizzes.questions');
        Route::post('/admin/quizzes/{quiz}/questions', [AdminQuizController::class, 'storeQuestion'])->name('admin.quizzes.questions.store');
        Route::put ('/admin/quizzes/{quiz}/questions/{question}', [AdminQuizController::class, 'updateQuestion'])->name('admin.quizzes.questions.update');
        Route::delete('/admin/quizzes/{quiz}/questions/{question}', [AdminQuizController::class, 'destroyQuestion'])->name('admin.quizzes.questions.destroy');

        // Admin CRUD routes for users
        Route::get ('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [DashboardController::class, 'storeUser'])->name('admin.users.store');
        Route::get ('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get ('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put ('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('/admin/users/bulk-delete', [AdminUserController::class, 'bulkDelete'])->name('admin.users.bulk-delete');
        Route::post('/admin/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::post('/admin/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset-password');

        // Phishing simulation routes
        Route::get('/admin/phishing', [PhishingController::class, 'index'])->name('admin.phishing');
        Route::post('/admin/phishing', [PhishingController::class, 'store'])->name('admin.phishing.store');
        Route::post('/admin/phishing/{campaign}/launch', [PhishingController::class, 'launch'])->name('admin.phishing.launch');
        Route::post('/admin/phishing/{campaign}/complete', [PhishingController::class, 'complete'])->name('admin.phishing.complete');
        Route::delete('/admin/phishing/{campaign}', [PhishingController::class, 'destroy'])->name('admin.phishing.destroy');
    });

    // Instructor routes
    Route::middleware('role:instructor')->group(function () {
        Route::get('/instructor/dashboard', [DashboardController::class, 'index'])->name('instructor.dashboard');
        Route::get('/instructor/courses', [DashboardController::class, 'instructorCourses'])->name('instructor.courses');
        Route::get('/instructor/students', [DashboardController::class, 'instructorStudents'])->name('instructor.students');
        Route::get('/instructor/students/export', [DashboardController::class, 'exportStudents'])->name('instructor.students.export');
        Route::get('/instructor/assessments', [DashboardController::class, 'instructorAssessments'])->name('instructor.assessments');
        Route::get('/instructor/assessments/export', [DashboardController::class, 'exportAssessments'])->name('instructor.assessments.export');
    });

    // Module action routes - require authentication
    Route::post('/modules/{module}/enroll', [ModuleController::class, 'enroll'])->name('modules.enroll');
    Route::post('/modules/{module}/unenroll', [ModuleController::class, 'unenroll'])->name('modules.unenroll');
    Route::post('/modules/{module}/complete', [ModuleController::class, 'complete'])->name('modules.complete');
    Route::put('/modules/{module}/progress', [ModuleController::class, 'updateProgress'])->name('modules.updateProgress');

    Route::get('/learn/{module}', [ModuleController::class, 'showLearningPath'])->name('learn.show');

    // Lesson routes
    Route::get('/modules/{module}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/modules/{module}/lessons/{lesson}/complete', [LessonController::class, 'markComplete'])->name('lessons.complete');
    Route::post('/modules/{module}/lessons/{lesson}/incomplete', [LessonController::class, 'markIncomplete'])->name('lessons.incomplete');

    // Message routes
    Route::get('/messages/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');

    // Student routes
    Route::middleware('role:student')->group(function () {
        Route::get('/student/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/student/courses', [DashboardController::class, 'studentCourses'])->name('student.courses');
        Route::get('/student/quizzes', [DashboardController::class, 'studentQuizzes'])->name('student.quizzes');
        Route::get('/student/quizzes/{quiz}', [ModuleController::class, 'showQuiz'])->name('student.quizzes.show');
        Route::post('/student/quizzes/{quiz}/submit', [ModuleController::class, 'submitQuiz'])->name('student.quizzes.submit');
        Route::get('/student/certificates', [DashboardController::class, 'studentCertificates'])->name('student.certificates');
        Route::get('/student/certificates/{certificate}/download', [DashboardController::class, 'downloadCertificate'])->name('student.certificates.download');
        Route::get('/student/inbox', [DashboardController::class, 'studentInbox'])->name('student.inbox');
    });

    Route::get('/student/leaderboard', [DashboardController::class, 'studentLeaderboard'])->name('student.leaderboard');
});

// Module view routes - accessible to guests (catalog browsing)
Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');

// Phishing tracking routes (public for simulation)
Route::get('/phishing/{campaign}/click', [PhishingController::class, 'trackClick'])->name('phishing.track-click');
Route::post('/phishing/{campaign}/report', [PhishingController::class, 'reportPhishing'])->name('phishing.report');
