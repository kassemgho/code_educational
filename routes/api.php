<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClassclcearController;
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
//admin
Route::post('login', [AuthController::class, 'login']);
Route::group(['prefix' => 'adminstrator' , 'middleware' => ['auth:sanctum','adminstrator']] , function(){
    Route::get('test' , function(){
       // return auth()->user()->with('adminstrator')->first();
        return ClassclcearController::kassem()  ;
    });
});

Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','teacher']] , function(){
    
});
Route::group(['prefix' => 'student' , 'middleware' => ['auth:sanctum','student']] , function(){
    
});

Route::get('tttt' ,[ClassclcearController::class , 'kassem']) ;