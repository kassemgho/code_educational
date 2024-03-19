<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Category;
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
        $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
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
        $assessment->active = 0;
        $problem = $assessment->problem;
        $problem->active = 1;
        $assessment->save();
        $problem->save();
    }
    
}
