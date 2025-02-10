<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index(Request $request){
        return response()->json(Student::all(), 200);
    }

    public function store(Request $request ){
        $request->validate([
           'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string|max:15',
            'age'=>'required|string|max:15',
        ]);

        $student = Student::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'phone'=> $request->phone,
             'age'=> $request->age,
        ]);
        return response()->json($student , 201);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        return response()->json($student, 200);
    }
    public function update(Request $request , $id){
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message'=> 'student not found'],404);
    }
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:students,email,' . $id,
        'phone' => 'sometimes|string|max:15',
        'age' => 'sometimes|string|max:15',
    ]);

    $student->update($request->all());
    return response()->json([
        'message' => 'Student updated successfully',
        'student' => $student
    ] , 200);
}

    public function destroy($id){
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message' => 'student not found' , 404]);
        }

        $student->delete();
        return response()->json(['message => student deleted successfully'], 200);
    }
}
