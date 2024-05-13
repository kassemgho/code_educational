<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorySubjectResource;
use App\Http\Resources\ExamsDisplay;
use App\Models\Category;
use App\Models\ChangeCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function myCategories(){
        // $data = [];
        $categories = auth()->user()->student->categories; 
        $data['categories'] = CategorySubjectResource::collection($categories);
        $exams = auth()->user()->student->exams()->where('time','>' ,now())
        ->orderBy('time')
        ->get() ;
        return $data;
    }
    public function changeCategory(Request $request){
        $request->validate([
            'old_category' => 'required|exists:categories,id',
            'new_category' => 'required|exists:categories,id',
            'reason' => 'required'
        ]);
        
        $student = auth()->user()->student;
        
        $student->requests()->create($request->all());
        return ['message' => 'added successfully'] ; 

    }

    
}
