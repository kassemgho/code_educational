<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $teacher = auth()->user()->teacher;
        $teacher->categories;
        return $teacher;
    }
    
}
