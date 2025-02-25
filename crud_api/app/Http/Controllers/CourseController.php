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
        try{
            $courses = Course::with("student")->get()->map(function ($course){
                return[
                    'id'=> $course->id,
                     'title'=> $course->title,
                     'description'=> $course->description
                ];
            });
            return response()->json($courses , 200);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch courses', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'title'=> 'required | string | max:255',
                'description'=> 'required | string | max:255',
            ]);

            $course = Course::create([
                 'title'=> $request->title,
                 'description'=> $request->description
            ]);
            return response()->json([
                'message'=>['course created successfully'],
                'course'=> $course
            ], 200);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create course', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $course = Course::find($id);
            if (!$course){
              return response()->json(['erorr' => 'course not found' ], 404);
        }
        return response()->json($course, 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'course not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch course', 'message' => $e->getMessage()], 500);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $course = Course::find($id);
            if (!$course){
                return response()->json(['error'=> 'course not found'],404);
            }
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description'=> 'sometimes|string|max:255',
            ]);
            $course->update($request->all());
            return response()->json([
                    'message'=> 'course updated successfully',
                    'course' => $course
                ], 200);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'course not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update course', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $course = Course::find($id);
            if (!$course){
                return response()->json(['erorr => course not found'],404);
            }
            $course->delete();
            return response()->json(['message => course deleted successfully'],200);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete course', 'message' => $e->getMessage()], 500);
        }
    }
}
