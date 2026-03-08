<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::middleware('throttle:api')->prefix('admin')->controller(AdminController::class)->group(function(){
Route::post('register','register');
Route::post('login','login');
Route::delete('deletecourse/{id}','deletecourse')->middleware('auth:admin');
Route::delete('deletelesson/{id}','deletelesson')->middleware('auth:admin');
Route::put('edit','edit')->middleware('auth:admin');
Route::put('updatepassword','updatepassword')->middleware('auth:admin');
Route::put('approvedcourse/{id}','approvedcourse')->middleware('auth:admin');
});


Route::middleware('throttle:api')->prefix('instructor')->controller(InstructorController::class)->group(function(){
Route::post('register','register');
Route::post('login','login');
Route::get('my-students','my_student')->middleware('auth:instructor');
Route::put('edit','edit')->middleware('auth:instructor');
Route::put('updatepassword','updatepassword')->middleware('auth:instructor');
Route::put('updatecourse/{id}','updatecourse')->middleware('auth:instructor');
Route::get('getlesson/{id}','getlesson')->middleware('auth:instructor');
Route::get('getallreviews','getreviews')->middleware('auth:instructor');
Route::get('mycourses','mycourses')->middleware('auth:instructor');
Route::put('updatestatus/{id}','updatestatus')->middleware('auth:instructor');

});
Route::middleware('throttle:api')->prefix('student')->controller(StudentController::class)->group(function(){
Route::post('register','register');
Route::post('login','login');
Route::post('join','join')->middleware('auth:student');
Route::get('getallcourses','courses')->middleware('auth:student');
Route::get('getmycourses','hiscourses')->middleware('auth:student');
Route::get('getmylessons','hislessons')->middleware('auth:student');
Route::post('addreview','AddReview')->middleware('auth:student');
Route::put('edit','edit')->middleware('auth:student');
Route::put('updatepassword','updatepassword')->middleware('auth:student');
});

Route::post('instructor/sendmessage',[MessageController::class,'store'])->middleware('auth:instructor');
Route::post('student/sendmessage',[MessageController::class,'store'])->middleware('auth:student');
Route::get('getmessages',[MessageController::class,'getmessages'])->middleware('auth:instructor,student');
Route::prefix('course')->middleware('auth:instructor')->controller(CourseController::class)->group(function(){
Route::post('add','add');
});
Route::prefix('lesson')->middleware('auth:instructor')->controller(LessonController::class)->group(function(){
Route::post('add','add');
});


