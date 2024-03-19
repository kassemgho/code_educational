<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Teacher\CategoryController;
use App\Http\Controllers\Teacher\ExamController;
use App\Http\Controllers\Teacher\MarkController;
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
        Route::get('/bank' , [ProblemController::class , 'showBank']);
        Route::get('/active/{problem}' , [ProblemController::class , 'activate']);
        Route::get('my-problems' , [ProblemController::class , 'myProblems']);
        Route::get('/{problem}', [ProblemController::class, 'show']);
    });
    Route::group(['prefix' => 'categories'] , function(){
        Route::get('/' , [CategoryController::class, 'index']);
        Route::get('/{category}' , [CategoryController::class , 'show']);
        Route::post('/add-marks' , [MarkController::class , 'addMarks']);
        Route::post('/attendance',[CategoryController::class , 'checkStudents']);
        Route::post('/{category}' , [CategoryController::class , 'updateCategory']);
      
    });
    Route::group(['prefix' => 'exams'] , function(){
        Route::post('/edit-student-mark' , [ExamController::class, 'editMarkStudent']);
        Route::get('/{category}' , [CategoryController::class , 'show']);
    });
});
Route::group(['prefix' => 'student' , 'middleware' => ['auth:sanctum','student']] , function(){
    
});

