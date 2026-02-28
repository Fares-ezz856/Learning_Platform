<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class GeminiController extends Controller
{
    // 1. عرض صفحة الدردشة
    public function index()
    {
        return view('gemini-chat');
    }

    // 2. معالجة السؤال
public function ask(Request $request)
{
    $request->validate([
        'prompt' => 'required|string|max:2000',
    ]);

  try {
    // استخدمي هذه الطريقة المباشرة
    $result = Gemini::model('models/gemini-1.5-flash')->generateContent($request->prompt);

    return back()->with([
        'question' => $request->prompt,
        'answer'   => $result->text(),
    ]);
} catch (\Exception $e) {
    // هذا السطر سيجلب لنا رسالة الخطأ الحقيقية من جوجل إذا فشل
    return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
}
}
}
