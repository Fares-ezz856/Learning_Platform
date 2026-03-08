<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
{
    // تحديد المستخدم من أي جارد
    $user = auth()->guard('instructor')->check()
            ? auth()->guard('instructor')->user()
            : auth()->guard('student')->user(); // الـ default guard للطالب مثلاً

    $message = $user->messages()->create([
        'message' => $request->message
    ]);

    // بث الرسالة عبر Reverb
    broadcast(new MessageSent($message))->toOthers();

    return response()->json($message);
}
public function getmessages(){
    $messages=Message::with('sender')->orderBy('created_at','asc') ->get();
    return response()->json([
        'status' => 'success',
        'data' => $messages
    ]);
}
}
