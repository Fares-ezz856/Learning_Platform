<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\StudentRequest;
use App\Models\Course;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
      use ApiResponse;
    public function register(StudentRequest $request){
        $validated=$request->validated();
            $admin=Student::create($validated);
            $token=$admin->createToken('Student-Token')->plainTextToken;
            return $this->success('Registered Successfully',201,$token);
    }
    public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $email=Student::where('email',$request->email)->first();
        if(!$email || !password_verify($request->password,$email->password)){
            return $this->error('Credintiols is false',401);
        }
        $token=$email->createToken('Student-Login')->plainTextToken;
        return $this->success('Login Successfully',200,$token);
    }
    public function join(Request $request){
        $student=auth('student')->user();
     $validated = $request->validate([
        'course_id' => 'required',
        'course_id.*' => 'array|exists:courses,id'
    ]);
      $student->courses()->syncWithoutDetaching($request->course_id);
      return $this->success('Joined successfully', 200);
    }
    public function courses(){
        $courses=Course::all();
        if($courses->isempty()){
            return $this->success('No Found Courses',200);
        }
        return $this->success('This is all courses',200,$courses);
    }
    public function hiscourses(){
        $student=auth('student')->user();
        $courses=$student->courses()->with('instructor:id,name')->get();

        if ($courses->isempty()) {
        return $this->success('You have not joined any courses yet.', 200);
    }
        return $this->success('This is Your Courses',200,$courses);
    }
    public function hislessons(){
        $student=auth('student')->user();
        $lessons=$student->courses()->with('lessons:id,title,course_id')->get();
             if ($lessons->isempty()) {
        return $this->success('You have not joined any lessons yet.', 200);
    }
        return $this->success('This is Your Courses',200,$lessons);
    }
    public function AddReview(ReviewRequest $reviewRequest){
        $reviewRequest->validated();
        Review::create([
            'message'=>$reviewRequest->message,
            'student_id'=>auth('student')->id(),
            'instructor_id'=>$reviewRequest->instructor_id
        ]);
        return $this->success('Review Added Successfully',201);
    }

        public function edit(Request $request){
        $student=auth('student')->user();
        $validated=$request->validate([
            'name'=>'sometimes,min|3',
        ]);
        $student->update($validated);
        return $this->success('Profile updated successfully', 200, $student);
    }

    public function updatepassword(Request $request){
        $student=auth('student')->user();
        $password=$student->password;
        $request->validate([
            'password'=>'required',
            'new_password'=>'required|min:6|confirmed'
        ]);
      if (!Hash::check($request->password, $password)) {
        return $this->error('Your current password is wrong', 401);
    }
            $student->update([
                'password'=>Hash::make($request->new_password)
            ]);
            return $this->success('Password Updated successfully',200);
    }
    }

