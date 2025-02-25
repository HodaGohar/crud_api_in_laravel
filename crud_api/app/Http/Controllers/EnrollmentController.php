<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\enrollment;
use App\Models\student;
use App\Models\course;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $enrollments = Enrollment::with(['student' ,'course'])->get()->map(function($enrollment){
                return [
                    'id' => $enrollment->id,
                    'student' => $enrollment->student ? $enrollment->student->name : 'Unknown Student',
                    'course' => $enrollment->course ? $enrollment->course->title : 'Unknown Course',
                ];
            });
            return response()->json($enrollments , 200);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Enrollments', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
           $request->validate([
              'student_id' => 'required|integer|max:255',
              'course_id' => 'required|integer|max:255',
           ]);
           $enrollment = Enrollment::create([
               'student_id' => $request->student_id,
               'course_id' => $request->course_id
           ]);
           return response()->json([
            'message'=> 'Enrollment created successfully',
            'enrollment' => $enrollment
        ],200);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create enrollment', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
       $enrollment = Enrollment::find($id);
       if (!$enrollment){
        return response()->json(['message' => 'Enrollment not found'] , 404);
       }
        return response()->json($enrollment , 200);

    }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Student not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch student', 'message' => $e->getMessage()], 500);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       try{
        $enrollment = Enrollment::find($id);
        if (!$enrollment){
            return response()->json(['message'=> 'Enrollment not found'] ,404);
        }
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);
         $enrollment->update($request->all());
         return response()->json([
            'message'=> 'Enrollment updated successfully',
            'Enrollment' => $enrollment
       ], 200);
       }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Student not found'], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update student', 'message' => $e->getMessage()], 500);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $enrollment = Enrollment::find($id);
            if (!$enrollment){
                return response()->json(['message'=> 'Enrollment not found'] , 404);
            }
            $enrollment->delete();
            return response()->json(['message'=> 'Enrollment deleted successfully'], 200);

        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Enrollment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete Enrollment', 'message' => $e->getMessage()], 500);
        }
    }
}
