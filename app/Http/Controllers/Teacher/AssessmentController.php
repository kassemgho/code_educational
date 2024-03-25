<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Category;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    //show all my assessment_categories:
    public function index(Category $category) {
        return $category->assessments->map(function($assessment){
            return [
                'problem_name' =>$assessment->problem()->name ,
                'problem_id' => $assessment->problem_id ,
            ];
        });
    }

    public function store(Request $request){
        $category = Category::find($request->category_id);
        $category_teacher_id = $category->subjectTeacher->teacher->id ;
        if ($category_teacher_id != auth()->user()->teacher->id){
            return response()->json([
                'message' => 'this category does not belong to you'
            ],403);
        }
        $request->validate([
            'problem_id' => 'required|integer|exists:problems,id',
            'category_id' => 'required|integer|exists:categories,id'
        ]);
        $teacher = auth()->user()->teacher;
        $teacher->assessments()->create($request->all());
        return response()->json([
            'message' => 'assessment added successfully'
        ]);
    }
    public function stopAssessment(Assessment $assessment) {
        //secuity 
        if ($assessment->teacher_id != auth()->user()->teacher->id)
            return response()->json([
                'message' => 'this assessment does not belong to you '
            ],403);
        if ($assessment->active == 0)
            return response()->json([
                'mesage' => 'the assessment already stop'
            ],409);
        $assessment->active = 0;
        $problem = $assessment->problem;
        $problem->active = 1;
        $assessment->save();
        $problem->save();
        return response()->json([
            'message' => 'stopped successfully'
        ],200) ;
    }
    public function activeAssessment(Assessment $assessment) {
        //secuity 
        if ($assessment->teacher_id != auth()->user()->teacher->id)
            return response()->json([
                'message' => 'this assessment does not belong to you '
            ],403);
        $assessment->active = 1;
        $assessment->save();
        return response()->json([
            'message' => 'activeted successfully'
        ],200) ;
    }
    
    
}
