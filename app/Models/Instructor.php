<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Instructor extends Authenticatable
{
    use HasApiTokens;
    protected $guarded = [];
     protected $hidden = ['password', 'remember_token'];

     public function courses(){
        return $this->hasMany(Course::class);
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
public function reviews(){
    return $this->hasMany(Review::class);
}
}
