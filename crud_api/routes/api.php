<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/students', [StudentController::class , 'index']);
Route::post('/students', [StudentController::class , 'store']);
Route::get('/students/{student}', [StudentController::class ,'show']);
Route::put('/students/{student}' , [StudentController::class ,'update']);
Route::delete('/students/{student}' , [StudentController::class ,'destroy']);

Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class , 'store']);
Route::get('/courses/{course}', [CourseController::class , 'show']);
Route::put('/courses/{course}', [CourseController::class , 'update']);
Route::delete('/courses/{course}', [CourseController::class , 'destroy']);

Route::get('/enrollments' , [EnrollmentController::class , 'index']);
Route::post('/enrollments', [EnrollmentController::class ,'store']);
Route::get('/enrollments/{enrollment}', [EnrollmentController::class ,'show']);
Route::put('/enrollments/{enrollment}', [EnrollmentController::class ,'update']);
Route::delete('/enrollments/{enrollment}', [EnrollmentController::class ,'destroy']);
