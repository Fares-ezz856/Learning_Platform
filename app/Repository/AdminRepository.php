<?php
namespace App\Repository;

use App\Interface\AdminInterface;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;

class AdminRepository implements AdminInterface{
    public function register(array $data)
    {
        $admin=Admin::create($data);
        return $admin;
    }

 public function deletecourse($id)
 {
    $course=Course::find($id);

    $course->delete();
    return $course;
 }

 public function  deletelesson($id)
 {
    $lesson=Lesson::find($id);
    $lesson->delete();
    return $lesson;
 }

 public function dashboard()
 {
            $instructor=Instructor::count();
        $student=Student::count();
        $course=Course::count();
        $pending_course=Course::where('status','pending')->count();
        $approved_course=Course::where('status','approved')->count();

         $data=[
            'instructor'=>$instructor,
            'student'=>$student,
            'course'=>$course,
            'pending_course'=>$pending_course,
            'approved_course'=>$approved_course
        ];
 return $data;
 }
}
