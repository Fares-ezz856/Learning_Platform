<?php

use App\Http\Controllers\GeminiController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::middleware(['auth:admin_web'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardView'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Admin Course Management Routes
    Route::get('/admin/courses', [AdminController::class, 'allCourses'])->name('admin.courses.index');
    Route::get('/admin/courses/pending', [AdminController::class, 'pendingCourses'])->name('admin.courses.pending');
    Route::post('/admin/courses/{id}/approve', [AdminController::class, 'approvedcourseWeb'])->name('admin.courses.approve');
    Route::post('/admin/courses/{id}/reject', [AdminController::class, 'rejectedcourseWeb'])->name('admin.courses.reject');
    Route::delete('/admin/courses/{id}', [AdminController::class, 'deletecourseWeb'])->name('admin.courses.delete');

    // Admin User Management Routes
    Route::get('/admin/instructors', [AdminController::class, 'allInstructors'])->name('admin.instructors.index');
    Route::delete('/admin/instructors/{id}', [AdminController::class, 'deleteinstructorWeb'])->name('admin.instructors.delete');

    Route::get('/admin/students', [AdminController::class, 'allStudents'])->name('admin.students.index');
    Route::delete('/admin/students/{id}', [AdminController::class, 'deletestudentWeb'])->name('admin.students.delete');
});

Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
Route::post('/gemini/ask', [GeminiController::class, 'ask'])->name('gemini.ask');
