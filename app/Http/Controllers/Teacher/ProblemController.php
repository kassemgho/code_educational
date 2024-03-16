<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\CodeExecutorController;
use App\Http\Controllers\Controller;
use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    /*
    {
	"problem_statment": "Dynamic problem", 
	"teacher_code_solve": "include<iostream>",
	"language_type": 1,
	"test_cases": [
		"1 2",
		"2 4",
		"3 6",
	],	
    }
    */ 
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'teacher_solve_code' => 'required',
            'language' => 'required|integer',
            'test_cases' => 'required|array'
        ]);
        $problem = Problem::create($request->all());
        $results = [];
        foreach($request->test_cases as $test_case) {
            
            $input = $test_case;
            if ($request->language == 1)
                $output = CodeExecutorController::runCppCode(new Request([
                    'teacher_solve_code' => $request->teacher_solve_code,
                    'input' => $test_case,
                ]));
            else $output = CodeExecutorController::runJavaCode(new Request([
                    'teacher_solve_code' => $request->teacher_solve_code,
                    'input' => $test_case,
                ]));
            $problem->testCases()->create([
                'input'=>$input ,
                'output' => $output ,
            ]);
            $output = json_decode($output, true); 
            if (array_key_exists('error', $output)) {
                return $problem->testCases ;
            }
            
        }

    }
}
