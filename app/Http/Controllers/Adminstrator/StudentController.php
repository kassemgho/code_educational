<?php

namespace App\Http\Controllers\Adminstrator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SetOfStudent;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function importStudents(Request $request ){
        $request->validate([
            'file' => 'required'
        ]);
        $file = $request->file('file');
        $rows = Excel::toCollection([] , $file)[0];
        foreach($rows as $row){
            if ($row[0] == 'number')continue ;
            SetOfStudent::create([
                'number' =>$row[0] ,
                'name' =>  $row[1]
            ]);
        }
    }
        public function distributeCategories(Request $request){
        $request->validate([
            'classes' => 'required|integer',
            'year' => 'required|integer',
            'file' => 'required',
        ]);
        $subjects = ($request->year == 1) ? [1,2] : [3 ,4 ,5] ;
        $file = $request->file('file');
        $rows = Excel::toCollection([] , $file)[0];
        $this->distribute($request , $subjects , $rows);
        return response()->json([
            'message' =>  'added successfully' ,
        ]);
    }
    private function distribute($request , $subjects , $rows){
         for ($i = 1; $i <= $request->classes * count($subjects); $i++) {
            $subject_id = $subjects[(int)(($i-1)/$request->classes)] ;
            $subject = Subject::where('id' , $subject_id)->first();
            
            $categories [] = Category::create([
                'subject_id' => $subject->id ,
                'name' => $subject->name.'_'.$i ,
            ]);
        }
        foreach ($rows as $row){
            if ($row[0] == 'number') continue;
            $user = User::where('name', $row[1])->first();
            $student = $user->student ;
            foreach($categories as $category){
                if ($category->name[strlen($category->name)-1] == $row[0]){
                    $student->categories()->attach($row[0]);
                }
            }
        }
    }
}

