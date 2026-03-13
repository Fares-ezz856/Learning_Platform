<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\StudentRequest;
use Gemini\Laravel\Facades\Gemini;

use App\Models\Review;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;

use App\Models\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $lessons=$student->courses()->wherePivot('status','approved')->with('lessons:id,title,course_id')->get();
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

    public function dashboardView(){
        $student = auth('student_web')->user();
        if (!$student) {
            return redirect()->route('student.login');
        }
        $course_count = $student->courses()->count();
        $approved_courses = $student->courses()->wherePivot('status', 'approved')->get();
        $lesson_count = 0;
        foreach ($approved_courses as $course) {
            $lesson_count += $course->lessons()->count();
        }
        
        $enrolled_courses = $student->courses()->with('instructor')->get();

        $lessons = collect();
        foreach ($approved_courses as $course) {
            $lessons = $lessons->merge($course->lessons()->with('course')->get());
        }
        $lessons = $lessons->sortByDesc('created_at')->take(10); // Show most recent 10 lessons

        // Chart Data: Enrollment Type Distribution
        $statusDistribution = [
            'approved' => $student->courses()->wherePivot('status', 'approved')->count(),
            'pending' => $student->courses()->wherePivot('status', 'pending')->count(),
            'rejected' => $student->courses()->wherePivot('status', 'rejected')->count(),
        ];

        return view('student.dashboard', compact('student', 'course_count', 'lesson_count', 'enrolled_courses', 'statusDistribution', 'lessons'));
    }

    public function myCoursesWeb()
    {
        $student = auth('student_web')->user();
        $courses = $student->courses()->with('instructor')->get();
        return view('student.courses.index', compact('courses'));
    }

    public function courseLessonsWeb($courseId)
    {
        $student = auth('student_web')->user();
        $course = $student->courses()->where('course_id', $courseId)->wherePivot('status', 'approved')->with('lessons')->firstOrFail();
        return view('student.courses.lessons', compact('course'));
    }

    public function browseCoursesWeb()
    {
        $student = auth('student_web')->user();
        $joinedCourseIds = $student->courses()->pluck('courses.id')->toArray();
        
        $courses = Course::where('status', 'approved')
            ->whereNotIn('id', $joinedCourseIds)
            ->with('instructor')
            ->withCount('students')
            ->get();
            
        return view('student.courses.browse', compact('courses'));
    }

    public function joinCourseWeb($id)
    {
        $student = auth('student_web')->user();
        
        if ($student->courses()->where('course_id', $id)->exists()) {
            return redirect()->back()->with('info', 'You have already requested to join this course.');
        }

        $course = Course::findOrFail($id);

        // If the course is paid, redirect to payment checkout
        if (!$course->isFree()) {
            return redirect()->route('student.payment.checkout', $id);
        }

        // Free course: enroll directly
        $student->courses()->attach($id, ['status' => 'pending']);
        
        return redirect()->route('student.courses.index')->with('success', 'Your request to join the course has been sent to the instructor.');
    }

    public function paymentCheckoutWeb($id)
    {
        $student = auth('student_web')->user();
        $course = Course::with('instructor')->findOrFail($id);

        // Prevent double enrollment
        if ($student->courses()->where('course_id', $id)->exists()) {
            return redirect()->route('student.courses.index')->with('info', 'You are already enrolled in this course.');
        }

        // Free courses don't need payment
        if ($course->isFree()) {
            return redirect()->route('student.courses.join', $id);
        }

        return view('student.payment.checkout', compact('course'));
    }

    public function processPaymentWeb(Request $request, $id)
    {
        $student = auth('student_web')->user();
        $course = Course::findOrFail($id);

        // Prevent double enrollment
        if ($student->courses()->where('course_id', $id)->exists()) {
            return redirect()->route('student.courses.index')->with('info', 'You are already enrolled in this course.');
        }

        $request->validate([
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'cardholder_name' => 'required_if:payment_method,credit_card|string|max:255',
            'card_number' => 'required_if:payment_method,credit_card|string|max:19',
            'expiry' => 'required_if:payment_method,credit_card|string|max:5',
            'cvv' => 'required_if:payment_method,credit_card|string|max:4',
        ]);

        // Create payment record (simulated – always succeeds)
        $payment = Payment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'payment_method' => $request->payment_method,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'status' => 'completed',
        ]);

        // Enroll student in course
        $student->courses()->attach($course->id, ['status' => 'pending']);

        return redirect()->route('student.courses.index')->with('success', 'Payment of $' . number_format($course->price, 2) . ' completed successfully! Your enrollment is pending instructor approval.');
    }

    public function paymentHistoryWeb()
    {
        $student = auth('student_web')->user();
        $payments = $student->payments()->with('course')->latest()->get();
        return view('student.payment.history', compact('payments'));
    }

    public function profileViewWeb()
    {
        $student = auth('student_web')->user();
        return view('student.profile', compact('student'));
    }

    public function profileUpdateWeb(Request $request)
    {
        $student = auth('student_web')->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $student->name = $validated['name'];
        $student->email = $validated['email'];
        if ($request->filled('password')) {
            $student->password = Hash::make($validated['password']);
        }
        $student->save();
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function askAI(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        $student = auth('student_web')->user();
        $courseNames = $student->courses()->wherePivot('status', 'approved')->pluck('title')->implode(', ');

        $systemContext = "You are a helpful AI study assistant for a student named {$student->name} on an educational platform. ";
        if ($courseNames) {
            $systemContext .= "The student is currently enrolled in these courses: {$courseNames}. ";
        }
        $systemContext .= "Help them with their studies, answer questions about their courses, explain concepts, suggest study tips, and motivate them. Keep answers concise and helpful. Respond in the same language the student uses.";

        $fullPrompt = $systemContext . "\n\nStudent's question: " . $request->prompt;

        try {
            $result = Gemini::generativeModel('gemini-2.0-flash')->generateContent($fullPrompt);
            return response()->json([
                'success' => true,
                'answer' => $result->text(),
            ]);
        } catch (\Exception $e) {
            Log::error('Gemini AI Error in Student Assistant: ' . $e->getMessage(), [
                'exception' => $e,
                'student_id' => $student->id
            ]);
            return response()->json([
                'success' => false,
                'answer' => 'Sorry, I could not process your request. Please try again later. (Error: ' . $e->getMessage() . ')',
            ], 500);
        }
    }
}
