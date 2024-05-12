<?php

use App\Http\Controllers\Adminstrator\CategoryController as AdminstratorCategoryController;
use App\Http\Controllers\Adminstrator\StudentController as AdminStudentController;
use App\Http\Controllers\Adminstrator\ExamController as AdminstratorExamController;
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
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Student\CategoryController as StudentCategoryController;
use App\Http\Controllers\Student\ContestController;
use App\Http\Controllers\Student\ProblemController as StudentProblemController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Teacher\AuthController as TeacherAuthController;
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

Route::post('student/register', [StudentAuthController::class, 'register']);

Route::group(['prefix' => 'adminstrator' , 'middleware' => ['auth:sanctum','adminstrator']] , function(){
    Route::get('teachers', [TeacherController::class, 'index']);
    Route::post('teachers/add', [TeacherController::class, 'addTeacher']);
    Route::get('categories-with-subjects', [AdminstratorCategoryController::class, 'categoriesWithSubjects']);
    Route::get('exams', [AdminstratorExamController::class, 'index']);
    Route::get('exams/{exam}', [AdminstratorExamController::class, 'show']);
    Route::post('add-exam/{subject}', [AdminstratorExamController::class, 'addExamToSubject']);
    Route::get('students', [AdminStudentController::class, 'index']);
    Route::post('students/{student}/change-password', [AdminStudentController::class, 'changeStudentPassword']);
    Route::post('students/import', [AdminStudentController::class, 'importStudents']);
    Route::post('add-teacher2category' , [TeacherController::class , 'assignmentTeacherToCategory']);
    Route::get('categries-no-teacheruniversity_ids' , [AdminstratorCategoryController::class , 'categoriesWihtNoTeacher']);
    Route::group(['prefix' => 'students'] , function(){
        Route::post('distribute' , [AdminStudentController::class , 'distribut\eCategories']);
    });
});
    

Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','teacher']] , function(){
    Route::post('login' , [TeacherAuthController::class , 'login']);
    Route::group(['prefix' => 'profile'] , function(){
        Route::get('/' , [TeacherProfileController::class ,'show']);
        Route::post('/' , [TeacherProfileController::class ,'update']);
        Route::post('change-password' , [TeacherProfileController::class , 'changePassword']) ;
        
    });
    Route::post('add-tag' , [TagController::class , 'addTag']);

    Route::group(['prefix' => 'problems'] , function(){
        Route::post('/' , [ProblemController::class, 'store']);
        Route::delete('/{problem}' , [ProblemController::class , 'delete']);
        Route::get('/' , [ProblemController::class , 'index']);
        Route::post('fillter' , [StudentProblemController::class , 'filter']);
        Route::get('/bank' , [ProblemController::class , 'showBank']);
        Route::get('/active/{problem}' , [ProblemController::class , 'activate']);
        Route::get('my-problems' , [ProblemController::class , 'myProblems']);
        Route::get('tags' , [TagController::class , 'tags']);
        Route::get('/{problem}', [ProblemController::class, 'show']);
        Route::post('add-tag' , [TagController::class , 'addTag']);
        Route::post('generate-test-cases' , [ProblemController::class , 'generateTestCases']);
    });
    Route::group(['prefix' => 'categories'] , function(){
        Route::get('/min' , [CategoryController::class, 'index']);
        Route::get('/{category}/students' , [CategoryController::class , 'showCategoryStudent']);
        Route::post('/add-marks' , [MarkController::class , 'addMarks']);
        Route::post('{category}/attendance/',[CategoryController::class , 'checkStudents']);
        Route::post('/{category}' , [CategoryController::class , 'updateCategory']);
    });

    Route::group(['prefix' => 'exams'] , function(){
        Route::post('/edit-student-mark' , [ExamController::class, 'editMarkStudent']);
        Route::post('/answers' , [ExamController::class, 'show']);
    });
    Route::group(['prefix' => 'assessment'] , function(){
        Route::get('/{category}' ,[ AssessmentController::class , 'index']) ;
        Route::post('/create' , [AssessmentController::class, 'store']);
        Route::post('/stop/{assessment}' , [AssessmentController::class , 'stopAssessment']);
        Route::post('/check-attendance/{assessment}' , [AssessmentController::class , 'checkStudents']);
        Route::delete('/{assessment}' , [AssessmentController::class , 'delete']) ;
    });
});
//kassem
Route::group(['prefix' => 'student' , 'middleware' => ['auth:sanctum','student']] , function(){
    Route::group(['prefix' => 'problems'] , function(){
        Route::post('solve/{problem}' , [StudentProblemController::class , 'solve']);
        Route::get('/' , [StudentProblemController::class , 'problems']);
        Route::post('fillter' , [StudentProblemController::class , 'filter']);
        Route::get('{problem}' , [StudentProblemController::class , 'show']);
        Route::get('/test-cases/{solve}' ,[StudentProblemController::class , 'testCases']);
        Route::get('/solves/{problem}' , [StudentProblemController::class , 'solves']);
        // Route::get('/solution/{id}' , [StudentProblemController::class , ''])
    });
    Route::group(['prefix' => 'profile'] , function(){
        Route::get('/' , [ProfileController::class , 'show']);
        
    });
    Route::group(['prefix' => 'contests'] , function(){
        Route::get('/', [ContestController::class, 'myContests']);
        Route::post('create' , [ContestController::class , 'create']);
        Route::post('{contest}/solve/{problem}' , [ContestController::class , 'solve']);
        Route::post('join/{contest}' , [ContestController::class , 'join']);
        
        Route::get('/{contest}' , [ContestController::class , 'show']) ;
    });
    Route::group(['prefix' => 'categories'] , function(){
        Route::get('/' , [StudentCategoryController::class , 'myCategories']);
    });
});


Route::post('test' , [ExcelImportController::class , 'test']) ;
Route::get('testf' , [ExcelImportController::class , 'test']);
Route::post('run' , function(Request $request){
    $param['input'] = $request->input ;
    $param['code'] = $request->code ;
    return CodeExecutorController::runCPPCode($param);
});



//toker   
//  5|qTNH44fjNepK8vNKiCywtkpedjmVbsLKckwKLdTp2cf119fb