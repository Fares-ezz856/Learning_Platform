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
    return $this->belongsToMany(Student::class,'student_courses')->withPivot('status')->withTimestamps();
   }
   public function lessons(){
    return $this->hasMany(Lesson::class);
   }
   public function payments(){
    return $this->hasMany(Payment::class);
   }
   public function isFree(){
    return $this->price <= 0;
   }
}
