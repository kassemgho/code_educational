<?php

use App\Http\Controllers\Adminstrator\CategoryController as AdminstratorCategoryController;
use App\Http\Controllers\Adminstrator\StudentController as AdminStudentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CodeExecutorController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\CategoryController;
use App\Http\Controllers\Teacher\ExamController;
use App\Http\Controllers\Teacher\MarkController;
use App\Http\Controllers\Teacher\ProblemController;
use App\Http\Controllers\Teacher\TagController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use App\Http\Controllers\Adminstrator\TeacherController;
use App\Http\Controllers\Student\ContestController;
use App\Http\Controllers\Student\ProblemController as StudentProblemController;
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
    Route::post('add-teacher2category' , [TeacherController::class , 'assignmentTeacherToCategory']);
    Route::get('categries-no-teachers' , [AdminstratorCategoryController::class , 'categoriesWihtNoTeacher']);
    Route::group(['prefix' => 'students'] , function(){
        Route::post('distribute' , [AdminStudentController::class , 'distributeCategories']);
    });
});
    

Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','teacher']] , function(){
    Route::group(['prefix' => 'profile'] , function(){
        Route::get('/' , [TeacherProfileController::class ,'show']);
        Route::post('/' , [TeacherProfileController::class ,'update']);
        
    });
    Route::post('add-tag' , [TagController::class , 'addTag']);

    Route::group(['prefix' => 'problems'] , function(){
        Route::post('/' , [ProblemController::class, 'store']);
        Route::delete('/{problem}' , [ProblemController::class , 'delete']);
        Route::get('/' , [ProblemController::class , 'index']);
        Route::get('/bank' , [ProblemController::class , 'showBank']);
        Route::get('/active/{problem}' , [ProblemController::class , 'activate']);
        Route::get('my-problems' , [ProblemController::class , 'myProblems']);
        Route::get('/{problem}', [ProblemController::class, 'show']);
        Route::post('add-tag' , [TagController::class , 'addTag']);
        Route::post('generate-test-cases' , [ProblemController::class , 'generateTestCases']);
    });
    Route::group(['prefix' => 'categories'] , function(){
        Route::get('/min' , [CategoryController::class, 'myCategory']);
        Route::get('/{category}/students' , [CategoryController::class , 'showCategoryStudent']);
        Route::post('/add-marks' , [MarkController::class , 'addMarks']);
        Route::post('/attendance',[CategoryController::class , 'checkStudents']);
        Route::post('/{category}' , [CategoryController::class , 'updateCategory']);
      
    });
    Route::group(['prefix' => 'exams'] , function(){
        Route::post('/edit-student-mark' , [ExamController::class, 'editMarkStudent']);
        Route::post('/answers' , [ExamController::class, 'show']);
    });
    Route::group(['prefix' => 'assessment'] , function(){
        Route::post('/create' , [AssessmentController::class, 'store']);
        Route::get('/stop/{assessment}' , [AssessmentController::class , 'stopAssessment']);
        Route::get('/active/{assessment}' , [AssessmentController::class , 'activeAssessment']);
    });
});
Route::group(['prefix' => 'student' , 'middleware' => ['auth:sanctum','student']] , function(){
    Route::group(['prefix' => 'problem'] , function(){
        Route::post('solve/{problem}' , [StudentProblemController::class , 'solve']);
    });
    Route::group(['prefix' => 'contests'] , function(){
        Route::post('create' , [ContestController::class , 'create']);
        Route::post('{contest}/solve/{problem}' , [ContestController::class , 'solve']);
    });
});
Route::post('test' , [ExcelImportController::class , 'test']) ;
Route::get('testf' , [ExcelImportController::class , 'test']);
Route::post('run' , function(Request $request){
    $param['input'] = $request->input ;
    $param['code'] = $request->code ;
    return CodeExecutorController::runJavaCode($param);
});