<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    public function show(){
        $teacher = auth()->user()->teacher ;
        $teacher->user ;
        return $teacher ;
    }
    public function update(Request $request){
        $teacher = auth()->user()->teacher ;
        $user = auth()->user() ;
        
        $teacher->update($request->all()) ;
        $user->update($request->all());
        $teacher->user ;
        return response()->json([
            'data' => $teacher , 
            'message' => 'updated successfully'
        ]);
    }
}
