<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\CodeExecutorController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProblemResource;
use App\Models\Problem;
use App\Models\ProblemTag;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    
 
    public function index(){
        return ProblemResource::collection(Problem::all());
    }
    public function myProblems() {
        $teacher = auth()->user()->teacher;
        $problems = $teacher->problems;
        return response()->json([
            'data' => $problems
        ], 200);
    }
    public function store(Request $request) {
        $teacher = auth()->user()->teacher;
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'teacher_code_solve' => 'required',
            'language' => 'required|integer',
            'test_cases' => 'required|array',
            'tags' => 'required|array'
        ]);
        
        $problem = $teacher->problems()->create($request->all());
        // add tags to problem
        foreach($request->tags as $tag)
            $problem->tags()->attach($tag);
        // add test_cases to problem
        foreach($request->test_cases as $test_case) {
            $input = $test_case;
            if ($request->language == 1)
                $output = CodeExecutorController::runCppCode([
                    'teacher_code_solve' => $request->teacher_code_solve,
                    'input' => $test_case,
                ]);
            else $output = CodeExecutorController::runJavaCode([
                    'teacher_code_solve' => $request->teacher_code_solve,
                    'input' => $test_case,
                ]);
            if (array_key_exists('error', $output)) {
                $problem->delete() ;
                return response()->json([
                    'input' => $test_case ,
                    'output' => $output,
                    
                ]);
            }
            $problem->testCases()->create([
                'input'=>$input ,
                'output' => $output['output'],
            ]);
        }
     return $problem->testCases ;   
    }
    public function show(Problem $problem) {
        $problem->tags;
        return $problem ;
    }
    public function delete(Problem $problem){
         if (auth()->user()->teacher->id != $problem->teacher_id){
            abort(403 , 'this problem does not belong to you' ) ;
        } 
        $problem->delete();
        return response()->json([
            'message' => 'deleted successfully' ,
        ],200) ;
    }
    public function activate(Problem $problem){
        if (auth()->user()->teacher->id != $problem->teacher_id){
            abort(403 , 'this problem dont belong to you' ) ;
        } 
        $problem->active = 1 ;
        return response()->json([
            'message' => 'added successfully'
        ],200) ;
    }
}
