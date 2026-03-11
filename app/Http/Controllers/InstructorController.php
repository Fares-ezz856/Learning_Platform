<?php
namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorRequest;
use App\Http\Resources\ApprovedCourseResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\StudentResource;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    use ApiResponse;
    public function register(InstructorRequest $request)
    {
        $validated = $request->validated();
        $admin     = Instructor::create($validated);
        $token     = $admin->createToken('Instructor-Token')->plainTextToken;
        return $this->success('Registered Successfully', 201, $token);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
        $email = Instructor::where('email', $request->email)->first();
        if (! $email || ! password_verify($request->password, $email->password)) {
            return $this->error('Credintiols is false', 401);
        }
        $token = $email->createToken('Instructor-Login')->plainTextToken;
        return $this->success('Login Successfully', 200, $token);
    }

        public function logout(){
        if(auth('instructor')->user()){
            auth('instructor')->user()->currentAccessToken()->delete();
        return $this->success('Logout Successfully',200);
        }
        return $this->error('No Active session Found',401);

    }

    public function my_student()
    {
        $instructor_id     = auth('instructor')->id();
        $coursewithstudent = Course::where('instructor_id', $instructor_id)->with('students:id,name')->get();
        return $this->success('Students list by course', 200, StudentResource::collection($coursewithstudent));
    }
    public function edit(Request $request)
    {
        $instructor = auth('instructor')->user();
        $validated  = $request->validate([
            'name' => 'sometimes|min:3',
            'bio'  => 'sometimes',
        ]);
        $instructor->update($validated);
        return $this->success('Profile updated successfully', 200, $instructor);
    }

    public function updatepassword(Request $request)
    {
        $instructor = auth('instructor')->user();
        $password   = $instructor->password;
        $request->validate([
            'password'     => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        if (! Hash::check($request->password, $password)) {
            return $this->error('Your current password is wrong', 401);
        }
        $instructor->update([
            'password' => Hash::make($request->new_password),
        ]);
        return $this->success('Password Updated successfully', 200);
    }
    public function updatecourse($id, Request $request)
    {
        $instructor   = auth('instructor')->user();
        $instructorid = $instructor->id;
        $course       = Course::where('instructor_id', $instructorid)->where('status','approved')->find($id);
        if (! $course) {
            return $this->error('This Course Not Found', 404);
        }
        $validated = $request->validate([
            'title'       => 'sometimes|string',
            'description' => 'sometimes|string',

        ]);
        $course->update($validated);
        return $this->success('This Course Updated Successfully', 200);
    }
    public function getlesson($id)
    {
        $lessons = Lesson::findOrFail($id);
        if ($lessons->content_type != 'article') {
            $lessons->content_data = asset('/storage/' . $lessons->content_data);
        }
        return $this->success('This is Lesson', 200, $lessons);
    }

    public function getreviews()
    {
        $reviews = Review::where('instructor_id', auth('instructor')->id())->with('student:id,name')->get();
        if ($reviews->isEmpty()) {
            return $this->error('Not Found Any Review For You', 404);
        }
        return $this->success('This is Your Reviews', 200, ReviewResource::collection($reviews));
    }

 public function mycourses(){
    $courses=Course::where('instructor_id',auth('instructor')->id())->where('status','approved')->get();
    if($courses->isEmpty()){
        return $this->error('Not Found Any Courses',404);
    }
return $this->success('This is Your Courses',200,ApprovedCourseResource::collection($courses));
 }
public function updatestatus($id,Request $request){
    $request->validate([
        'student_id'=>'required|exists:students,id',
        'status'=>'required|in:pending,approved,rejected',
    ]);
    $course=Course::where('instructor_id',auth('instructor')->id())->findOrFail($id);
    $course->students()->updateExistingPivot($request->student_id,[
        'status'=>$request->status,
    ]);
    return $this->success('Student status updated successfully to '.$request->status,200);
}

public function countcourse(){
    $count=Course::where('instructor_id',auth('instructor')->user()->id)->count();
    $data=[
        'count'=>$count
    ];
    return $this->success('This is count of courses',200,$data);
}

public function dashboard(){
    $instructor_id = auth('instructor')->id();
    $course_count = Course::where('instructor_id', $instructor_id)->count();
    $student_count = Course::where('instructor_id', $instructor_id)->withCount('students')->get()->sum('students_count');
    $review_count = Review::where('instructor_id', $instructor_id)->count();

    $data = [
        'total_courses' => $course_count,
        'total_students' => $student_count,
        'total_reviews' => $review_count,
    ];
    return $this->success('Instructor Dashboard Data',200,$data);
}
}
