<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\CodeExecutorController;
use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\SolveProblem;
use Illuminate\Http\Request;
use PDO;

class ProblemController extends Controller
{
    public function problems(){
        $problems = Problem::with('tags')->get();
        $problems = $problems->reverse(); 
        return response()->json([
            'message' => 'done' ,
            'data' => $problems 
        ],200) ;
    }
    public function solves($problem){
        $submistions =  SolveProblem::where('student_id' , auth()->user()->student->id)
            ->where('problem_id' , $problem)
            ->get();
        $data = [] ;
        foreach($submistions as $submistion){
            if ($submistion->approved == true ){
                $message = 'accept' ;
            }else $message = 'error' ;
            $data [] = [
                'id' => $submistion->id ,
                'naem' => 'solution ' . $submistion->id ,
                'status' => $message ,
            ];
        }
        return response()->json([
            'message' =>  ' done ' ,
            'data' => $data
        ],200);
    }
    public function testCases(SolveProblem $solve){
        $solve->solveCases;  
        return $solve;
    }
    public function show(Problem $problem){
        $problem->testCase ;
        $problem->tags ;
        $solved = SolveProblem::where('student_id' , auth()->user()->student->id )
        ->where('problem_id' , $problem->id)
        ->where('approved', true)
        ->exists();
        if ($solved){
            $problem['status'] = 'accept' ;
        }else $problem['status'] = 'not yet' ;
        return $problem ;
    }
    public static function solveProlem(Problem $problem , Request $request):array{
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
                return [
                    'message' => $message,
                    'approved' => false 
                ];
            }
        }
        $solveProblem->approved = true ;
        $student->rate += $problem->level ;
        $student->points += $problem->level ;
        $solveProblem->save();
        return [
            'message' => 'accept' ,
            'approved' => true 
        ];
    }
    public function solve(Problem $problem , Request $request){
        $solve = $this->solveProlem($problem , $request) ;
        if ($solve['approved'] == false){
            return response()->json([
                    'message' => $solve['message'],
                    'approved' => false 
            ],300);
        }else {
            return response()->json([
                'message' => $solve['message'] ,
                'approved' => true 
            ],200);
        }
    }
    public function filter(Request $request){
    
        $problems = Problem::query()->with('tags') ;
        
        if ($request->diffculty != null){
            $problems->where('diffculty' , $request->diffculty);
        }
        if ($request->tag != null) {
            $problems->whereHas('tags', function ($query) use ($request) {
                $query->where('name', $request->tag);
        });
        if ($request->sort != null){
            if ($request->sort == 'AES')
                $problems->orderBy('level' , 'asc');
            else $problems->orderBy('level' , 'desc');
        }
        }
        
        return $problems->get() ;
    }

}
