<?php

namespace App\Models;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description'] ;


    public function students()
{
    return $this->belongsToMany(Student::class, 'enrollments');
}
public function enrollments()
{
    return $this->hasMany(Enrollment::class, 'student_id');
}
}
