<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssessmentStudentsResource;
use App\Models\Assessment;
use App\Models\Category;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index(Category $category) {
        return $category->assessments->map(function($assessment){
            return [
                'problem_name' =>$assessment->problem->name ,
                'problem_id' => $assessment->problem_id ,
                'id' => $assessment->id ,
                'name' => $assessment->name ,
            ];
        });
    }

    public function store(Request $request){
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'string' ,
            'problem_id' => 'required|exists:problems,id'
        ]);
        $request['teacher_id'] = auth()->user()->teacher->id;
        Assessment::create($request->all());
        return [
            'message' => 'assessment added successfully' 
        ];

    }
    public function stopAssessment(Assessment $assessment) {
        $assessment->active = 0 ; 
        $assessment->problem->in_bank = 0;
         $category = $assessment->category ;
        foreach($assessment->students as $student){
             $student->categories()->updateExistingPivot($category->id, [
        'assessment_marks' => $assessmentMarks,
        'attendance_marks' => \DB::raw('attendance_marks + 1')
    ]);
        }
    }
    public function checkStudents(Assessment $assessment , Request $request ) {
        $request->validate([
            'students' => 'required|array'
        ]);
        foreach($request->students as $student){
            $assessment->students()->attach($student) ;
        }
        return AssessmentStudentsResource::collection($assessment->students()->get());
    }
    
    
    
}
