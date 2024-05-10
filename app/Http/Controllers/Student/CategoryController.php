<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorySubjectResource;
use App\Http\Resources\ExamsDisplay;

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

    
}
