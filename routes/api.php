<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Teacher\CategoryController;
use App\Http\Controllers\Teacher\ProblemController;
use App\Http\Controllers\Teacher\TagController;
use App\Models\Problem;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['prefix' => 'adminstrator' , 'middleware' => ['auth:sanctum','adminstrator']] , function(){

});
    

Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','teacher']] , function(){

    Route::post('add-tag' , [TagController::class , 'addTag']);

    Route::group(['prefix' => 'problems'] , function(){
        Route::post('/' , [ProblemController::class, 'store']);
        Route::post('active/{problem}' , [ProblemController::class , 'activate']);
        Route::delete('/{problem}' , [ProblemController::class , 'delete']);
        Route::get('/' , [ProblemController::class , 'index']);
        Route::get('my-problems' , [ProblemController::class , 'myProblems']);
        Route::get('/{problem}', [ProblemController::class, 'show']);
    });
    Route::group(['prefix' => 'categories'] , function(){
        Route::get('/' , [CategoryController::class, 'index']);
    });
    
});
Route::group(['prefix' => 'student' , 'middleware' => ['auth:sanctum','student']] , function(){
    
});

