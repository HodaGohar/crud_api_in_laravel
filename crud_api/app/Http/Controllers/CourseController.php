<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

            $courses = Course::with("students")->get()->map(function ($course){
                return[
                    'id'=> $course->id,
                     'title'=> $course->title,
                     'description'=> $course->description
                ];
            });
            return response()->json($courses , 200);
        }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

            $data = $request->validate([
                'title'=> 'required | string | max:255',
                'description'=> 'required | string | max:255',
            ]);

            $course = Course::create($data);
            return response()->json([
                'message'=>['course created successfully'],
                'course'=> $course
            ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json($course, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {

            $data = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description'=> 'sometimes|string|max:255',
            ]);
            $course->update($data);
            return response()->json([
                    'message'=> 'course updated successfully',
                    'course' => $course
                ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
            $course->enrollments()->delete();
            $course->delete();
            return response()->json(['message => course deleted successfully'],200);

    }
}
