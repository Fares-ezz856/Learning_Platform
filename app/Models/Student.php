<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;



class Student extends Authenticatable
{
    use HasApiTokens;
   protected $guarded = [];
  protected $hidden = ['password', 'remember_token'];

  public function courses(){
    return $this->belongsToMany(Course::class,'student_courses');
  }
  public function reviews(){
    return $this->hasMany(Review::class);
  }
       protected function casts(): array
    {
        return [

            'password' => 'hashed',
        ];
    }
    public function messages() {
    return $this->morphMany(Message::class, 'sender');
}
}
