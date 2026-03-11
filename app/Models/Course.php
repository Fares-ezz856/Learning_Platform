<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
   protected $guarded = [];
   public function instructor(){
    return $this->belongsTo(Instructor::class);
   }
   public function students(){
    return $this->belongsToMany(Student::class,'student_courses');
   }
   public function lessons(){
    return $this->hasMany(Lesson::class);
   }
}
