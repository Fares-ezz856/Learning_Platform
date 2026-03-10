<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\InstructorRequest;
use App\Http\Requests\StudentRequest;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    use ApiResponse;
    public function register(AdminRequest $request){
        $validated=$request->validated();
            $admin=Admin::create($validated);
            $token=$admin->createToken('Admin-Token')->plainTextToken;
            return $this->success('Registered Successfully',201,$token);
    }
    public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $email=Admin::where('email',$request->email)->first();
        if(!$email || !password_verify($request->password,$email->password)){
            return $this->error('Credintiols is false',401);
        }
        $token=$email->createToken('Admin-Login')->plainTextToken;
        return $this->success('Login Successfully',200,$token);
    }
    public function logout(){
        if(auth('admin')->user()){
            auth('admin')->user()->currentAccessToken()->delete();
        return $this->success('Logout Successfully',200);
        }
        return $this->error('No Active session Found',401);

    }
    public function deletecourse($id){
        $course=Course::find($id);
        if(!$course){
            return $this->error('This Course Not Found',200);
        }
        $coursename=$course->name;
        $course->delete();
        $course->lessons()->delete();
        return $this->success('This Course '.$coursename.'Deleted Successfully',200);
    }
    public function deletelesson($id){
        $lesson=Lesson::find($id);
            if(!$lesson){
            return $this->error('This Lesson Not Found',200);
        }
        $lesson->delete();
        return $this->success('This Lesson'.$lesson->name.'Deleted Successfully',200);
    }

        public function edit(Request $request){
        $admin=auth('admin')->user();
        $validated=$request->validate([
            'name'=>'sometimes|min:3',
        ]);
        $admin->update($validated);
        return $this->success('Profile updated successfully', 200, $admin);
    }

    public function updatepassword(Request $request){
        $admin=auth('admin')->user();
        $password=$admin->password;
        $request->validate([
            'password'=>'required',
            'new_password'=>'required|min:6|confirmed'
        ]);
      if (!Hash::check($request->password, $password)) {
        return $this->error('Your current password is wrong', 401);
    }
            $admin->update([
                'password'=>Hash::make($request->new_password)
            ]);
            return $this->success('Password Updated successfully',200);
    }

       public function approvedcourse($id)
    {
        $course = Course::find($id);
        if (! $course) {
            return $this->error('This Course Not Found', 404);
        }
        $course->update([
            'status' => 'approved',
        ]);
        return $this->success('Your Course' . $course->name . ' Approved Successfully', 200);
    }
    public function rejectedcourse($id){
        $course=Course::find($id);
          if (! $course) {
            return $this->error('This Course Not Found', 404);
        }
        $course->update([
            'status' => 'rejected',
        ]);
        return $this->success('Your Course' . $course->name . ' Rejected Successfully', 200);
    }

    public function data(){
        $instructor=Instructor::count();
        $student=Student::count();
        $course=Course::count();
        $data=[
            'instructor'=>$instructor,
            'student'=>$student,
            'course'=>$course
        ];
        return $this->success('This is Count Of Instructor And Student',200,$data);
    }
    public function deletestudent($id){
        $student=Student::find($id);

        if(!$student){
            return $this->error('This Student Not Found',200);
        }
         $studentname=$student->name;
        $student->delete();
        return $this->success('This Student '.$studentname. ' Deleted Successfully',200);
    }
    public function deleteinstructor($id){
        $instructor=Instructor::find($id);
        if(!$instructor){
            return $this->error('This Instructor Not Found',200);
        }
        $instructorname=$instructor->name;
        $instructor->delete();
        return $this->success('This Instructor '.$instructorname.' Deleted Successfully',200);
    }
    public function addstudent(StudentRequest $studentRequest){
        $validated=$studentRequest->validated();
        Student::create($validated);
        return $this->success('Student Added Successfully',201);
    }
        public function addinstructor(InstructorRequest $instructorRequest){
        $validated=$instructorRequest->validated();
        Instructor::create($validated);
        return $this->success('Instructor Added Successfully',201);
    }
    }
