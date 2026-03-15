<?php
namespace App\Repository;

use App\Interface\StudentInterface;
use App\Models\Course;
use App\Models\Student;

class StudentRepository implements StudentInterface{
    public function register(array $data)
    {
        $student=Student::create($data);
        return $student;
    }
    public function courses()
    {
         $courses=Course::all();
         return $courses;
    }
    public function mycourses()
    {
           $student=auth('student')->user();
        $courses=$student->courses()->with('instructor:id,name')->get();
        return $courses;
    }
    public function mylessons()
    {
          $student=auth('student')->user();
        $lessons=$student->courses()->wherePivot('status','approved')->with('lessons:id,title,course_id')->get();
        return $lessons;
    }
}
