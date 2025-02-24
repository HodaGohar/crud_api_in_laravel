<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description'] ;

    public function students(){
       return $this->belongsToMany(student::class,'enrollments', 'student_id', 'course_id');
    }
}
