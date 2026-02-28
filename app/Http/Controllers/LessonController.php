<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;

class LessonController extends Controller
{
    use ApiResponse;
    public function add(LessonRequest $lessonRequest){
        $validated=$lessonRequest->validated();
        Lesson::create($validated);
        return $this->success('Lesson Added Successfully',201);
    }
}
