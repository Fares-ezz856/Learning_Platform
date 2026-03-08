<?php

namespace App\Http\Controllers;

use App\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    use ApiResponse;
    public function add(Request $request){
      $request->validate([
        'title'=>'required',
        'course_id'=>'required|exists:courses,id',
        'file'=>'required_if:content_type,video,pdf,image',
        'article_body'=>'required_if:content_type,article',

      ]);
      $data = [
        'course_id' => $request->course_id,
        'title' => $request->title,
        'content_type' => $request->content_type,
       
    ];
    if($request->content_type==='article'){
        $data['content_data']=$request->article_body;
    }
    else{
        $path = $request->file('file')->store('lessons_files', 'public');
        $data['content_data'] = $path;
    }
    Lesson::create($data);
    return $this->success('Lesson Create Successfully',201);
    }



}
