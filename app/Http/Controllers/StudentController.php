<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\StudentRequest;
use App\Mail\ContactMail;
use App\Models\Contact;
use Gemini\Laravel\Facades\Gemini;

use App\Models\Review;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Repository\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class StudentController extends Controller
{
    private $repo;
    public function __construct(StudentRepository $studentRepository)
    {
        $this->repo=$studentRepository;
    }
      use ApiResponse;
    public function register(StudentRequest $request){
        $validated=$request->validated();
            $student=$this->repo->register($validated);
            $token=$student->createToken('Student-Token')->plainTextToken;
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

          public function logout(){
        if(auth('student')->user()){
            auth('student')->user()->currentAccessToken()->delete();
        return $this->success('Logout Successfully',200);
        }
        return $this->error('No Active session Found',401);

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
        $courses=$this->repo->courses();
        if($courses->isempty()){
            return $this->success('No Found Courses',200);
        }
        return $this->success('This is all courses',200,$courses);
    }
    public function hiscourses(){

        $courses=$this->repo->mycourses();

        if ($courses->isempty()) {
        return $this->success('You have not joined any courses yet.', 200);
    }
        return $this->success('This is Your Courses',200,$courses);
    }
    public function hislessons(){

        $lessons=$this->repo->mylessons();
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

    public function dashboard()
    {
        $student = auth('student')->user();
        if (!$student) {
            return $this->error('Unauthorized', 401);
        }

        $course_count = $student->courses()->count();
        $approved_courses = $student->courses()->wherePivot('status', 'approved')->get();

        $lesson_count = 0;
        foreach ($approved_courses as $course) {
            $lesson_count += $course->lessons()->count();
        }

        $enrolled_courses = $student->courses()->with('instructor:id,name')->get();

        return $this->success('Student Dashboard Data', 200, [
            'stats' => [
                'course_count' => $course_count,
                'lesson_count' => $lesson_count,
            ],
            'enrolled_courses' => $enrolled_courses,
        ]);
    }

    public function courseDetails($id)
    {
        $course = Course::with(['instructor:id,name,bio', 'lessons:id,title,course_id,order'])->findOrFail($id);

        // Check if student is joined
        $student = auth('student')->user();
        $is_joined = $student->courses()->where('course_id', $id)->exists();
        $status = $is_joined ? $student->courses()->where('course_id', $id)->first()->pivot->status : null;

        return $this->success('Course Details', 200, [
            'course' => $course,
            'enrollment_status' => $status,
            'is_joined' => $is_joined
        ]);
    }

    public function lessonDetails($id)
    {
        $lesson = Lesson::with('course')->findOrFail($id);
        $student = auth('student')->user();

        // Check enrollment and approval
        $is_approved = $student->courses()
            ->where('course_id', $lesson->course_id)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$is_approved) {
            return $this->error('You are not enrolled in this course or your enrollment is not approved yet.', 403);
        }

        if ($lesson->content_type != 'article') {
            $lesson->content_data = asset('/storage/' . $lesson->content_data);
        }

        return $this->success('Lesson Details', 200, $lesson);
    }

    public function searchCourses(Request $request)
    {
        $query = $request->query('query');
        if (!$query) {
            return $this->error('Search query is required', 400);
        }

        $courses = Course::where('status', 'approved')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->with('instructor:id,name')
            ->get();

        return $this->success('Search results', 200, $courses);
    }

    public function instructorProfile($id)
    {
        $instructor = Instructor::with(['courses' => function($q) {
            $q->where('status', 'approved');
        }])->findOrFail($id);

        return $this->success('Instructor Profile', 200, $instructor);
    }

    private function sendFcmNotification($token, $title, $body)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = env('FIREBASE_SERVER_KEY');
        if (!$serverKey) {
            Log::warning('FIREBASE_SERVER_KEY is not set in .env. Skipping notification.');
            return;
        }

        $headers = [
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ];

        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'icon' => asset('favicon.ico'),
                'click_action' => route('instructor.contacts.index'),
            ],
        ];

        try {
            \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post($fcmUrl, $payload);
        } catch (\Exception $e) {
            Log::error('FCM Notification Error: ' . $e->getMessage());
        }
    }
}
