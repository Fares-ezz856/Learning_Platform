<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorRequest;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
      use ApiResponse;
    public function register(InstructorRequest $request){
        $validated=$request->validated();
            $admin=Instructor::create($validated);
            $token=$admin->createToken('Instructor-Token')->plainTextToken;
            return $this->success('Registered Successfully',201,$token);
    }
    public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $email=Instructor::where('email',$request->email)->first();
        if(!$email || !password_verify($request->password,$email->password)){
            return $this->error('Credintiols is false',401);
        }
        $token=$email->createToken('Instructor-Login')->plainTextToken;
        return $this->success('Login Successfully',200,$token);
    }

    public function my_student(){
        $instructor_id=auth('instructor')->id();
        $coursewithstudent=Course::where('instructor_id',$instructor_id)->with('students:id,name')->get();
        return $this->success('Students list by course', 200, $coursewithstudent);
    }
    public function edit(Request $request){
        $instructor=auth('instructor')->user();
        $validated=$request->validate([
            'name'=>'sometimes,min:3',
            'bio'=>'sometimes'
        ]);
        $instructor->update($validated);
        return $this->success('Profile updated successfully', 200, $instructor);
    }

    public function updatepassword(Request $request){
        $instructor=auth('instructor')->user();
        $password=$instructor->password;
        $request->validate([
            'password'=>'required',
            'new_password'=>'required|min:6|confirmed'
        ]);
      if (!Hash::check($request->password, $password)) {
        return $this->error('Your current password is wrong', 401);
    }
            $instructor->update([
                'password'=>Hash::make($request->new_password)
            ]);
            return $this->success('Password Updated successfully',200);
    }
}
