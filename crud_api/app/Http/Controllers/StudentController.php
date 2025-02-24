<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        try {
            $students = Student::with('courses')->get()->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'image' => $student->image ? asset('storage/' . $student->image) : null,
                    'courses' => $student->courses->pluck('title')->implode(' | ')
                ];
            });

            return response()->json($students, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch students', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request ){
        try{
        $request->validate([
           'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'required|string|max:15',
            'age'=>'required|string|max:15',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('students', 'public');
        } else {
            $imagePath = null;
        }

        $student = Student::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'image'=> $imagePath,
            'phone'=> $request->phone,
             'age'=> $request->age,
        ]);
        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student,
            'image_url' => $student->image ? asset('storage/' . $student->image) : null,
        ],  201);
    }catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to create student', 'message' => $e->getMessage()], 500);
    }
    }

    public function show($id)
    {
        try{
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        return response()->json($student, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Student not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch student', 'message' => $e->getMessage()], 500);
    }
    }

    public function update(Request $request , $id){
        try{
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
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update student', 'message' => $e->getMessage()], 500);
        }
}

    public function destroy($id){
        try{
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message' => 'student not found' , 404]);
        }

        $student->delete();
        return response()->json(['message => student deleted successfully'], 200);
    }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Student not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete student', 'message' => $e->getMessage()], 500);
    }
    }

      public function enroll(Request $request , $student_id){
        try{
          $student = Student::find($student_id);
          if (!$student) {
            return response()->json(['message'=> 'student not found'],404);
            }
          $request->validate([
            'course_id' => 'required|exists:courses,id'
          ]);
          $student->courses()->attach($request->course_id);


          return response()->json(['message' => 'Student enrolled successfully'], 201);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to enroll student', 'message' => $e->getMessage()], 500);
        }
      }

      public function getCourses($student_id)
      {
        try{
          $student = Student::with('courses')->find($student_id);

          if (!$student) {
              return response()->json(['message' => 'Student not found'], 404);
          }

          return response()->json($student->courses, 200);
      }
    catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Student not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch courses', 'message' => $e->getMessage()], 500);
    }
}
}
