<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>اسأل Gemini AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; }
        .chat-box { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .response-area { background: #e9ecef; padding: 15px; border-radius: 10px; margin-top: 20px; white-space: pre-wrap; }
    </style>
</head>
<body>

<div class="container">
    <div class="chat-box">
        <h2 class="text-center mb-4">🤖 اسأل ذكاء Gemini الاصطناعي</h2>

        <form action="{{ route('gemini.ask') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="prompt" class="form-label">اكتب سؤالك هنا:</label>
                <textarea class="form-control" name="prompt" id="prompt" rows="3" placeholder="مثلاً: كيف أتعلم البرمجة؟" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">إرسال السؤال</button>
        </form>

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        @if(session('answer'))
            <hr>
            <div class="mt-4">
                <h5><strong>سؤالك:</strong></h5>
                <p>{{ session('question') }}</p>

                <h5 class="text-success"><strong>إجابة Gemini:</strong></h5>
                <div class="response-area text-dark">
                    {{ session('answer') }}
                </div>
            </div>
        @endif
    </div>
</div>

</body>
</html>
