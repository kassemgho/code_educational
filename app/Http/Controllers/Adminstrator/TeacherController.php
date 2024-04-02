<?php

namespace App\Http\Controllers\Adminstrator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function assignmentTeacherToCategory(Request $request){
        $request->validate([
            'teacher_id' => 'required|integer',
            'category_id' => 'required|integer'
        ]);
        // return $request ;
        $category = Category::findOrFail($request->category_id);
        
        $category->update([
            'teacher_id' => $request->teacher_id
        ]);
        return response()->json([
            'message' => 'added '
        ] ,200) ;
    }
    
}
