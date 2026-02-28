<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;

class CourseController extends Controller
{
    use ApiResponse;
    public function add(CourseRequest $courseRequest){
        $validated=$courseRequest->validated();
        Course::create($validated);
        return $this->success('course added successfully',201);
    }
}
