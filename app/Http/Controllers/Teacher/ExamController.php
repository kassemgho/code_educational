<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function show() {
        
    }
    public function editMarkStudent(Request $request) {
        $request->validate([
            'exam_id' => 'required|integer' ,
            'student_id' => 'required|integer' ,
            'mark' => 'required|integer'
        ]);
        $exam = ExamStudent::where('student_id' , $request->student_id)
            ->where('exam_id' , $request->exam_id)
            ->first() ;
        $exam->mark = $request->mark ;
        $exam->save();
        return response()->json([
            'message' => 'updated successfully'
        ],200);
    }
    
}
