<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\CodeExecutorController;
use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\SolveProblem;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    public function submitions(){
        $submistions =  SolveProblem::where('student_id' , auth()->user()->student->id)
        ->get();
        return response()->json([
            'message' =>  ' done ' ,
            'data' => $submistions
        ],200);
    }
    public function problemSubmitions($problem){
        $submistions =  SolveProblem::where('student_id' , auth()->user()->student->id)
            ->where('problem_id' , $problem)
            ->get();
        return response()->json([
            'message' =>  ' done ' ,
            'data' => $submistions
        ],200);
    }
    public function show(Problem $problem){
        return $problem->testCase;
    }
    public static function solve(Problem $problem , Request $request){
        //validate 
        $request->validate([
            'code' => 'required',
            'lang' => 'required|integer'
        ]);
        //create Solve problem 
        $student = auth()->user()->student ;
        $solveProblem = SolveProblem::create([
            'student_id' => $student->id ,
            'problem_id' => $problem->id ,
            'student_code' => $request->code ,
            'approved' => false ,
        ]);
        $time = 0 ; $message = "" ; $error = false ;
        // get test cases 
        $testCases = $problem->testCases()->get() ;
        //loop on each test case and check if work 
        foreach($testCases as $testCase){
            if ( $request->lang == 1)
                $output = CodeExecutorController::runCppCode(['code' => $request->code , 'input' => $testCase->input]);
            else $output = CodeExecutorController::runJavaCode(['code' => $request->code , 'input' => $testCase->input]);
            // retuen the error if accourd 
            
            if (array_key_exists('error' , $output)) {         
                $output = $output['error'];
                $time = 0 ;
                $message = "error " ;
                $error = true ;
            }else {
                $time = $output['time'];
                $output = $output['output']; 
                if ($time > $problem->time_limit_ms){
                    $message =  "time limit exceded .. the available time is $problem->time_limit_ms you get $time" ;               
                    $error  = true ;
                }
                if ($output != $testCase->output){
                    $message = "wrong test in one case ";
                    $error = true ;
                }
            }
            $solveProblem->solveCases()->create([
                'input'=> $testCase->input ,
                'output' => $output ,
                'time' => $time
            ]);
            if ($error){
                return response()->json([
                    'message' => $message,
                    'approved' => false 
                ],300);

            }
        }
        $solveProblem->approved = true ;
        $solveProblem->save();
        return response()->json([
            'message' => 'accept' ,
            'approved' => true 
        ],200);
    }
}
