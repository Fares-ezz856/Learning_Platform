<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the public chat room.
     */
    public function index()
    {
        $messages = Message::with('sender')->latest()->take(50)->get()->reverse();
        return view('chat.index', compact('messages'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sender = null;
        $guard = null;

        if (Auth::guard('admin_web')->check()) {
            $sender = Auth::guard('admin_web')->user();
            $guard = 'admin_web';
        } elseif (Auth::guard('instructor_web')->check()) {
            $sender = Auth::guard('instructor_web')->user();
            $guard = 'instructor_web';
        } elseif (Auth::guard('student_web')->check()) {
            $sender = Auth::guard('student_web')->user();
            $guard = 'student_web';
        }

        if (!$sender) {
            return redirect()->back()->with('error', 'You must be logged in to send messages.');
        }

        $sender->messages()->create([
            'message' => $request->message,
        ]);

        return redirect()->back();
    }
}
