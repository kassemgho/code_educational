<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryStudentResource;
use App\Models\Category;
use App\Models\CategoryStudent;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = auth()->user()->teacher->categories;
        return CategoryResource::collection($categories);
    }
    
    public function show(Category $category){
        $this->teacherAuth($category) ;
        $students = $category->students()->with(['exams' => function ($query) use ($category) {
            $query->where('subject_id', $category->subjectTeacher()->first()['subject_id']);
        }])->get();
        return CategoryStudentResource::collection($students);
    }

    public function checkStudents(Request $request){
        $request->validate([
            'student_ids' => 'required|array',
            'category_id' => 'required|integer' ,
        ]);
        
        foreach($request->student_ids as $student_id){
            $mark = CategoryStudent::where('student_id' ,$student_id )
                ->where('category_id' , $request->category_id)
                ->first() ;
            $mark->attendance_marks++ ;
            $mark->save() ;
        }
        return response()->json([
            'message' => 'checked successfully'
        ] ,200) ;
    }
    public function updateCategory(Request $request,Category $category){
        $this->teacherAuth($category) ;
        $category->update($request->all());
        return $category ;
    }
    


    protected function teacherAuth( Category $category){
        $teacher = $category->subjectTeacher()->first()->teacher;
        
        if ($teacher->id != auth()->user()->teacher->id){
            abort(403 , 'this category does not belong to you') ;
        }
    }
}
