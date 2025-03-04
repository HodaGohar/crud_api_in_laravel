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
            $enrollments = Enrollment::with(['student' ,'course'])->get()->map(function($enrollment){
                return [
                    'id' => $enrollment->id,
                    'student' => $enrollment->student ? $enrollment->student->name : 'Unknown Student',
                    'course' => $enrollment->course ? $enrollment->course->title : 'Unknown Course',
                ];
            });
            return response()->json($enrollments , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

           $data = $request->validate([
              'student_id' => 'required|integer|max:255',
              'course_id' => 'required|integer|max:255',
           ]);
           $enrollment = Enrollment::create($data);
           return response()->json([
            'message'=> 'Enrollment created successfully',
            'enrollment' => $enrollment
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        return response()->json($enrollment , 200);
    }

    /** }
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);
         $enrollment->update($data);
         return response()->json([
            'message'=> 'Enrollment updated successfully',
            'Enrollment' => $enrollment
       ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
            $enrollment->delete();
            return response()->json(['message'=> 'Enrollment deleted successfully'], 200);
    }
}
