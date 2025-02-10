<?php

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

Route::get('/students', [StudentController::class , 'index'])->name('students.index');
Route::post('/students', [StudentController::class , 'store'])->name('students.store');
Route::get('/show/{id}', [StudentController::class ,'show'])->name('students.show');
Route::put('/update/{id}' , [StudentController::class ,'update'])->name('students.update');
Route::delete('/delete/{id}' , [StudentController::class ,'destroy'])->name('students.delete');
