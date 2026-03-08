<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function instructor(){
        return $this->belongsTo(Instructor::class);
    }
}
