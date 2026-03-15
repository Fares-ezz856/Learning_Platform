<?php
namespace App\Repository;

use App\Interface\InstructorInterface;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Review;

class InstructorRepository implements InstructorInterface{
    public function register(array $data){
        $instrcutor=Instructor::create($data);
        return $instrcutor;
    }
    public function mystudent(){
          $instructor_id     = auth('instructor')->id();
        $coursewithstudent = Course::where('instructor_id', $instructor_id)->with('students:id,name')->get();
        return $coursewithstudent;
    }

    public function edit(array $data){
          $instructor = auth('instructor')->user();
          $instructor->update($data);
          return $instructor;
    }

    public function getlesson($id)
    {
        $lesson=Lesson::findOrFail($id);
        return $lesson;
    }

    public function getreviews()
    {
          $reviews = Review::where('instructor_id', auth('instructor')->id())->with('student:id,name')->get();
          return $reviews;
    }

    public function mycourses()
    {
         $courses=Course::where('instructor_id',auth('instructor')->id())->where('status','approved')->get();
         return $courses;
    }

}
