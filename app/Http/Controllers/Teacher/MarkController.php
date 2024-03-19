<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function addMarks(Request $request){
        $request->validate([
            'attendance_mark' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);
        $category = Category::find($request->category_id);
        $exam_mark = $category->subjectTeacher()->first()->subject()->first()->exam()->mark;
        return $exam_mark ;
        $total_mark  = $category->subjectTeacher()->subject()->total_mark ;
        $category->mark_of_commings = $request->attendance_mark;
        $category->mark_of_ratings = $total_mark - $category->mark_of_commings - $exam_mark ;
        $category->save();
        return response()->json([
            'message'=> 'mark added sucssessfully'
        ],200);
    }
}
