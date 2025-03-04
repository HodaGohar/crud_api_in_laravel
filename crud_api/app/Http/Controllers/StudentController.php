<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\course;
use App\Traits\HandlesImageUpload;

class StudentController extends Controller
{
    use HandlesImageUpload;
    public function index()
    {
            $students = Student::with('courses')->get()->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'image' => $student->image ? asset('storage/' . $student->image) : null,
                    'courses' => $student->courses->pluck('title')
                ];
            });

            return response()->json($students, 200);
    }

    public function store(Request $request ){

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'required|string|max:15',
            'age'=>'required|string|max:15',
        ]);

        $data['image'] = $this->uploadImage($request);
        // return 'hello';

         $student = Student::create($data);

        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student,
        ],  201);
    }

    public function show(Student $student)
    {
        return response()->json($student, 200);
    }

    public function update(Request $request ,Student $student){


     $data = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:students,email,' . $student->id,
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'phone' => 'sometimes|string|max:15',
        'age' => 'sometimes|string|max:15',
    ]);

    if($request->hasFile('image')){
        $this->deleteImage($student->image);
        $data['image'] = $this->uploadImage($request);
    }

    $student->update($data);

    return response()->json([
        'message' => 'Student updated successfully',
        'student' => $student
    ] , 200);

}

    public function destroy(Student $student){
        $student->enrollments()->delete();
        $this->deleteImage($student->image);
        $student->delete();
        return response()->json(['message' =>
        'Student deleted successfully'], 200);
    }

}
