<?php

namespace App\Http\Controllers\Adminstrator;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function categoriesWihtNoTeacher(){
        $categories = Category::where('teacher_id' , NULL)->get() ;
        return response()->json([
            'data' => $categories ,
            'message' => 'done'
        ],200);
    }
    
}
