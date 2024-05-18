<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryStudentResource;
use App\Models\Category;
use App\Models\CategoryStudent;
use App\Models\Student;
use App\Models\Subject;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index() {
        $categories = auth()->user()->teacher->categories;
        // return $categories ;
        return CategoryResource::collection($categories);
    }
    
    public function showCategoryStudent(Category $category){
        $students = $category->students()->get() ;
        // return $students ;
        
        return CategoryStudentResource::collection($students) ;
    }
    public function show(Category $category) {
        return $category;
    }
    public function checkStudents( Category $category,Request $request ){
        $request->validate([
            'students' => 'required|array',
        ]);
        
        DB::beginTransaction();
        // return $request ;
        foreach($request->students as $student){
            $studentCategoey = CategoryStudent::where('student_id' ,$student['id'] )
                ->where('category_id' , $category->id)
                ->first() ;
            if ($studentCategoey == null)abort(404,'there is no stududent in this category');
            $studentCategoey->presence++;
            if($student['mark']!=-1)
                $studentCategoey->number_of_assessment++;
            if($student['mark']!=0)
                $studentCategoey->assessment_marks+=$student['mark'] ;
            $studentCategoey->save() ;
        }
        DB::commit();
        return response()->json([
            'message' => 'checked successfully'
        ] ,200) ;
    }
    public function updateCategory(Request $request,Category $category){
        $this->teacherAuth($category) ;
        $request = $request->except(['name' , 'teacher_id'  , 'subject_id' ,'id']);
        $category->update($request);
        return new CategoryResource($category) ;
    }

    protected function teacherAuth( Category $category){
        $teacher = $category->teacher;       
        if ($teacher->id != auth()->user()->teacher->id){
            abort(403 , 'this category does not belong to you') ;
        }
    }

    
    
}
/**


 */