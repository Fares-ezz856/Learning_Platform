<?php

use App\Http\Controllers\GeminiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UserAuthController;
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

// Instructor Authentication (Web)
Route::get('/instructor/login', [UserAuthController::class, 'showInstructorLoginForm'])->name('instructor.login');
Route::post('/instructor/login', [UserAuthController::class, 'instructorLogin'])->name('instructor.login.submit');
Route::post('/instructor/logout', [UserAuthController::class, 'instructorLogout'])->name('instructor.logout');

// Student Authentication (Web)
Route::get('/student/login', [UserAuthController::class, 'showStudentLoginForm'])->name('student.login');
Route::post('/student/login', [UserAuthController::class, 'studentLogin'])->name('student.login.submit');
Route::post('/student/logout', [UserAuthController::class, 'studentLogout'])->name('student.logout');

// Student Dashboard Routes
Route::middleware(['auth:student_web'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboardView'])->name('student.dashboard');
    Route::get('/student/courses', [StudentController::class, 'myCoursesWeb'])->name('student.courses.index');
    Route::get('/student/browse-courses', [StudentController::class, 'browseCoursesWeb'])->name('student.courses.browse');
    Route::post('/student/courses/{id}/join', [StudentController::class, 'joinCourseWeb'])->name('student.courses.join');
    Route::get('/student/courses/{id}/lessons', [StudentController::class, 'courseLessonsWeb'])->name('student.courses.lessons');
});

// Instructor Dashboard Routes
Route::middleware(['auth:instructor_web'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboardView'])->name('instructor.dashboard');
    Route::get('/instructor/courses', [InstructorController::class, 'myCoursesWeb'])->name('instructor.courses.index');
    Route::get('/instructor/courses/create', [InstructorController::class, 'createCourseWeb'])->name('instructor.courses.create');
    Route::post('/instructor/courses', [InstructorController::class, 'storeCourseWeb'])->name('instructor.courses.store');
    Route::get('/instructor/courses/{id}/edit', [InstructorController::class, 'editCourseWeb'])->name('instructor.courses.edit');
    Route::put('/instructor/courses/{id}', [InstructorController::class, 'updateCourseWeb'])->name('instructor.courses.update');
    Route::delete('/instructor/courses/{id}', [InstructorController::class, 'destroyCourseWeb'])->name('instructor.courses.destroy');
    Route::get('/instructor/students', [InstructorController::class, 'myStudentsWeb'])->name('instructor.students.index');
    Route::post('/instructor/courses/{id}/status', [InstructorController::class, 'updateStudentStatusWeb'])->name('instructor.students.update-status');
});

Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
Route::post('/gemini/ask', [GeminiController::class, 'ask'])->name('gemini.ask');
