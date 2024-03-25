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
    
    public function showCategoryStudent(Category $category){
        // $this->teacherAuth($category) ;
       
        $students = $category->students()->with(['exams' => function ($query) use ($category) {
            $query->where('subject_id', $category->subjectTeacher()->first()['subject_id']);
        }])->get();
        return CategoryStudentResource::collection($students);
    }
    public function show(Category $category) {
        return $category;
    }
    public function checkStudents(Request $request){
        $request->validate([
            'students' => 'required|array',
            'category_id' => 'required|integer' ,
        ]);
        // return $request ;
        foreach($request->students as $student){
            $studentCategoey = CategoryStudent::where('student_id' ,$student['id'] )
                ->where('category_id' , $request->category_id)
                ->first() ;

            $studentCategoey->attendance_marks++;
            $studentCategoey->number_of_assessment++;
            $studentCategoey->assessment_marks+=$student['mark'] ;
            $studentCategoey->save() ;
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
/**


 */