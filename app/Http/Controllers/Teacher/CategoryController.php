<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryStudentResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = auth()->user()->teacher->categories;
        // $categories->map(function($category) {
        //     return $category->subjectTeacher->subject;
        // });
        //return $categories ;
        return CategoryResource::collection($categories);
    }
    public function show(Category $category){
        
        $teacher = $category->subjectTeacher()->first()->teacher;
    
        if ($teacher->user_id != auth()->id()){
            abort(403 , 'this category does not belong to you') ;
        }
        $students = $category->students()->with(['exams' => function ($query) use ($category) {
            // echo $category->subjectTeacher()->first()['subject_id'];
            $query->where('subject_id', $category->subjectTeacher()->first()['subject_id']);
        }])->get();
        return CategoryStudentResource::collection($students);
    }
}
