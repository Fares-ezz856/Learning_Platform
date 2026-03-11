<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    // Instructor Authentication
    public function showInstructorLoginForm()
    {
        if (Auth::guard('instructor_web')->check()) {
            return redirect()->route('instructor.dashboard');
        }
        return view('auth.instructor-login');
    }

    public function instructorLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('instructor_web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('instructor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function instructorLogout(Request $request)
    {
        Auth::guard('instructor_web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('instructor.login');
    }

    // Student Authentication
    public function showStudentLoginForm()
    {
        if (Auth::guard('student_web')->check()) {
            return redirect()->route('student.dashboard');
        }
        return view('auth.student-login');
    }

   public function studentLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 1. تأكد من عمل Logout لأي Guard آخر قبل محاولة دخول الطالب
    // ده بيمنع إن الـ Sidebar يظهر فيه Admin و Student مع بعض
    if (Auth::guard('admin_web')->check()) {
        Auth::guard('admin_web')->logout();
    }
    if (Auth::guard('instructor_web')->check()) {
        Auth::guard('instructor_web')->logout();
    }

    // 2. محاولة الدخول الخاصة بالطالب
    if (Auth::guard('student_web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {

        // 3. إعادة توليد الـ Session (مهم جداً للحماية)
        $request->session()->regenerate();

        return redirect()->intended(route('student.dashboard'));
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function studentLogout(Request $request)
    {
        Auth::guard('student_web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}
